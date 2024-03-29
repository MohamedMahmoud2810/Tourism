<?php

namespace App\Http\Controllers;

use App\Enums\ReportTypeEnum;
use App\Exports\StudentOverviewExport;
use App\Exports\StudentResultsExport;
use App\Exports\StudentStatisticsExport;
use App\Helpers\SubjectTrait;
use App\Http\Requests\StudentExportRequest;
use App\Models\Department;
use App\Models\Group;
use App\Models\GroupDepartmentSpecialize;
use App\Models\Result;
use App\Models\Specialize;
use App\Models\Student;
use App\Models\Studystatus;
use App\Models\Subject;
use App\Models\Trace;
use App\Models\YearSemester;
use App\Models\YearSemesterStudent;
use App\Queries\StudentQuery;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsentStudentsExport;

class StudentsReportsController extends Controller
{
    use SubjectTrait;
    public function show(Request $request)
    {
        if (!empty($request->all())) {
            $request->validate([
                'group_id' => 'required|integer|exists:groups,id',
                'departments_id' => 'required|integer|exists:departments,id',
                'specialize_id' => ['required', 'integer', 'exists:specializes,id',
                    function ($attr, $value, $fail) use ($request) {
                        $check = GroupDepartmentSpecialize::where('specialize_id', $value);
                        if (!is_null($request->group_id)) {
                            $check->where('group_id', $request->group_id);
                        }
                        if (!is_null($request->departments_id)) {
                            $check->where('department_id', $request->departments_id);
                        }
                        if (!$check->exists()) {
                            $fail('Error');
                        }
                    }],
                'status_id' => 'required|in:all,1,2,3',
                // 'yearsemester_id' => 'required|array|between:1,2',
                'yearsemester_id.*' => 'required|exists:yearsemester,id',
            ]);
            $check = GroupDepartmentSpecialize::query();
            if (!is_null($request->group_id)) {
                $check->where('group_id', $request->group_id);
            }
            if (!is_null($request->departments_id)) {
                $check->where('department_id', $request->departments_id);
            }
            if (!is_null($request->specialize_id)) {
                $check->where('specialize_id', $request->specialize_id);
            }
            $data = [
                'gds_id' => $check->get()->toArray(),
            ];
        } else {
            $data = [
                'gds_id' => 1,
            ];
        }
        /** filter request **/
        $groups = Group::where('id', '<', 5)->get();
        $departments = Department::all();
        $specializes = Specialize::all();
        $status = Studystatus::all();
        $year_semester = YearSemester::all();
        $subjects = Subject::where($data)->pluck('name')
            ->transform(function ($value) {
                return ['col' => 6, 'row' => 1, 'text' => $value];
            })->toArray();
        $data['group_id'] = $request->group_id ?? 1;
        $data['departments_id'] = $request->departments_id ?? 1;
        $data['specialize_id'] = $request->specialize_id ?? 1;
        if (!empty($request->all())) {
            $data['studystatuses_id'] = $request->status_id;
            $data['yearsemester_id'] = $request->yearsemester_id[0];
        } else {
            $data['studystatuses_id'] = 'all';
            $data['yearsemester_id'] = $year_semester->last()->id;
        }
        return view('Dashboard.students-reports.filter' , compact('subjects', 'groups', 'status', 'departments', 'specializes', 'year_semester', 'data'));
    }

    public function dataTableResultsStudents (Request $request)
    {
        $validated = Validator::make($request->all(), [
            'department_id' => 'required|exists:departments,id',
            'group_id' => 'required|exists:groups,id',
            'specialize_id' => ['required', 'exists:specializes,id',
                function ($attr, $value, $fail) use ($request) {
                    $check = GroupDepartmentSpecialize::where('specialize_id', $value);
                    if (!is_null($request->group_id)) {
                        $check->where('group_id', $request->group_id);
                    }
                    if (!is_null($request->departments_id)) {
                        $check->where('department_id', $request->departments_id);
                    }
                    if (!$check->exists()) {
                        $fail('Error');
                    }
                }],
            'yearsemester_id' => 'required',
            'yearsemester_id.*' => 'required|exists:yearsemester,id',
            'studystatuses_id' => 'required|in:1,2,3,all',
            'report_type' => 'required|in:1,2,3',
        ]);
        if ($validated->fails()) {
            return response(["draw" => 1,
                "recordsTotal" => 0,
                "recordsFiltered" => 0, 'data' => []], 200);
        }

        $data = $validated->validate();
        $groupId = $data['group_id'];
        $departmentId = $data['department_id'];
        $specializeId = $data['specialize_id'];
        $year = YearSemester::where('id' , $data['yearsemester_id'])->first();
        $statusId = $data['studystatuses_id'];
        $traceYear = Trace::where('yearsemester_id' , $year->id)->get();







        $studentsQuery = Student::with(['group', 'department', 'specialize', 'result'])
            ->where(function ($query) use ($groupId, $departmentId, $specializeId, $year , $statusId , $traceYear) {
                if (isset($traceYear[1]['action'])){
                    $query->join('yearsemester_student', function ($join) use ($groupId,$departmentId ,$specializeId, $year) {
                        $join->on('students.id', '=', 'yearsemester_student.student_id')
                            ->where('yearsemester_student.group_id', $groupId)
                            ->where('yearsemester_student.department_id', $departmentId)
                            ->where('yearsemester_student.specialize_id', $specializeId)
                            ->where('yearsemester_student.yearsemester_id', $year->id);
                    });
                } else {
                    $query->where('students.group_id', $groupId)
                        ->where('students.department_id', $departmentId)
                        ->where('students.specialize_id', $specializeId);
                }
                    if ($statusId !== 'all') {
                        $query->where('studystatuses_id', $statusId);
                    }
            });


        $results = Result::query()
            ->join('subjects', 'results.subjects_id', '=', 'subjects.id')
            ->join('students', 'results.students_id', '=', 'students.id')
            ->join('yearsemester', 'results.yearsemester_id', '=', 'yearsemester.id')
            ->join('studystatuses', 'students.studystatuses_id', '=', 'studystatuses.id')
            ->select([
                'results.id',
                'students.name as nameStd',
                'students.code as code',
                'students.bonus',
                'students.site_no as site_no',
                'studystatuses.name as status',
                'results.subjects_id',
                'results.bonus',
                'written',
                'applied',
                'kpis',
                'subjects.name as subject',
                'subjects.max_written',
                'subjects.max_kpis',
                'subjects.max_applied',
                'yearsemester.year as year',
                'yearsemester.semester as semester',
                'grade'
            ])
            ->selectRaw('written + applied + kpis + results.bonus as total')
            ->selectRaw('students.bonus as remaining_bonus')
            ->get();


        $students = $studentsQuery->get();

        $gradeCounts = [];
        $countAcceptedStudents = 0;
        $countGoodStudents = 0;
        $countVeryGoodStudents = 0;
        $countExcellentStudents = 0;
        $countWithOneSubject = 0;
        $countWithTwoSubject = 0;
        $absentStudents = 0;
        $failedStudents = 0;

        $studentSumOfGrades = 0;
        $percentage = 0;
        foreach ($students as $student){
            foreach($student->result as $studentResult){
                $totalForStudent = $studentResult->written + $studentResult->applied + $studentResult->bonus + $studentResult->kpis;
                $totalForAllSubjects = count($student->result)*100;
                $studentSumOfGrades += $totalForStudent;
                $percentage = ($studentSumOfGrades / $totalForAllSubjects)*100;
            }
            $gid = $request->group_id;
            $gradesString = $this->totalGrade($student->id);
            $finalYearGrade = $gradesString[$gid];
            $overallGrade = implode(', ', $gradesString);
            if (isset($gradeCounts[$overallGrade])) {
                $gradeCounts[$overallGrade]++;
            } else {
                $gradeCounts[$overallGrade] = 1;
            }
            $enrolledStudentsCount = $students->count();


            if ($gradesString['1'] === 'مقبول') {
                $countAcceptedStudents++;
            } elseif($gradesString['1'] === 'جيد') {
                $countGoodStudents++;
            }elseif($gradesString['1'] === 'جيد جدا') {
                $countVeryGoodStudents++;
            }elseif($gradesString['1'] === 'ممتاز') {
                $countExcellentStudents++;
            }elseif($gradesString['1'] === 'مادة') {
                $countWithOneSubject++;
            }
            elseif($gradesString['1'] === 'راسب') {
                $failedStudents++;
            } elseif($gradesString['1'] === 'غائب') {
                $absentStudents++;
            } elseif($gradesString['1'] === 'مادتين') {
                $countWithTwoSubject++;
            }
        }
        $succeededStudents = $countAcceptedStudents + $countGoodStudents + $countVeryGoodStudents + $countExcellentStudents + $countWithOneSubject + $countWithTwoSubject;
        $successPercentage = number_format(($succeededStudents / $enrolledStudentsCount)*100 , 2);

        $overview = [
            'enrolledStudentsCount' => $enrolledStudentsCount,
            'appliedStudentsCount' => $enrolledStudentsCount,
            'presentStudentsCount' => $enrolledStudentsCount-$absentStudents,
            'absentStudentsCount' => $absentStudents,
            'suspendedStudentsCount' => 0,
            'excellentStudentsCount' => $countExcellentStudents,
            'veryGoodStudentsCount' => $countVeryGoodStudents,
            'goodStudentsCount' => $countGoodStudents,
            'passStudentsCount' => $countAcceptedStudents,
            'failedStudentsCount' => $failedStudents,
            'succeededStudentsCount' => $succeededStudents,
            'oneSubjectFailedStudentCount' => $countWithOneSubject,
            'twoSubjectFailedStudentCount' => $countWithTwoSubject,
            'totalSuccessPercentage' => $successPercentage,
        ];

        switch ((int)$data['report_type']) {
            case 1:
                $view = 'export_students';
                $exportClass = new StudentResultsExport($results, $groupId, $departmentId, $specializeId, $year, $statusId);
                break;
            case 2:
                $view = 'students_overview';
                $exportClass = new StudentOverviewExport($groupId, $departmentId, $specializeId, $year, $statusId , $overview);
                break;
            case 3:
                $view = 'students_statistics';
                $exportClass = new StudentStatisticsExport($groupId, $departmentId, $specializeId, $year, $statusId , $overview);
                break;
            default:
                return abort(400, 'Unsupported report type');
        }
        return Excel::download($exportClass, $this->getExportFileName($data['report_type']));
    }




    private function getExportFileName($reportType): string
    {
        switch ($reportType) {
            case 1:
                return 'filtered_results.xlsx';
            case 2:
                return 'students_overview.xlsx';
            case 3:
                return 'students_statistics.xlsx';
            default:
                return 'undefined_report.xlsx';
        }
    }

    public function AbsentStudents()
    {
        $subjects = Subject::paginate();
        $groups = Group::whereNot('name', 'خريجين')->get();
        $departments = Department::paginate();
        $specializes = Specialize::paginate();
        $years = YearSemester::select('year')->distinct()->get();
        $semesters = YearSemester::select('semester')->distinct()->get();
        return view('Dashboard.Student.absent-student' , compact('subjects', 'specializes', 'groups', 'departments', 'years', 'semesters'));
    }


    public function ExportAbsentStudents (Request $request){
         $request->validate([
            'subjects_id' => 'required|exists:subjects,id',
            'year' => [
                'required', function ($attribute, $value, $fail) use ($request) {
                    if (!YearSemester::where('year', $value)->where('semester', $request->semester)->exists()) {
                        $fail('الفصل الدراسي مع هذه السنه غير موجود');
                    }
                }
            ],
            'semester' => 'required|string|min:3|max:8|in:دور تاني,اول,ثاني',
             'group_id' => 'required',
             'department_id' => 'required',
        ]);

        $subjectName = Subject::where('id' , $request->subjects_id)->first()->name;

        return Excel::download(new AbsentStudentsExport($subjectName), 'absent_students.xlsx');





    }


//    public function (){
//        $students = Student::with('Studystatus')->where('studystatuses_id' , 2)->get();
////            ->join('groups', 'students.group_id', '=', 'groups.id')
//
//        $columns = [[
//            ['col' => 1, 'row' => 2, 'text' => 'اسم الطالب'],
//            ['col' => 1, 'row' => 2, 'text' => 'كود الطالب'],
//            ['col' => 1, 'row' => 2, 'text' => 'رقم جلوس الطالب'],
//            ['col' => 1, 'row' => 2, 'text' => 'حالة الطالب'],
//        ]];
//        $subjects_names = [];
//        foreach ($students as $student){
//            $results = $student->result;
//            foreach ($results as $result){
//                $subject = Subject::find($result->subjects_id);
//                if ($subject){
//                        $subjects_names[] = $subject->name;
//                        $columns[1][] = ['col' => 1, 'row' => 1, 'text' => 'التحريري'];
//                        $columns[1][] = ['col' => 1, 'row' => 1, 'text' => 'التطبيقي'];
//                        $columns[1][] = ['col' => 1, 'row' => 1, 'text' => 'اعمال السنة'];
//                        $columns[1][] = ['col' => 1, 'row' => 1, 'text' => 'درجات الرافة'];
//                        $columns[1][] = ['col' => 1, 'row' => 1, 'text' => 'الدرجة الكلية'];
//                        $columns[1][] = ['col' => 1, 'row' => 1, 'text' => 'التقدير'];
//                }
//            }
//
//        }
//
//        $columns[0] = array_merge($columns[0], array_map(function ($subjectName) {
//            return ['col' => 1, 'row' => 2, 'text' => $subjectName];
//        }, $subjects_names));
//
//        return view('Dashboard.Student.absent-student', compact('students', 'results', 'subject', 'columns', 'subjects_names'));
//
//    }
}
