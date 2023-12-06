<?php

namespace App\Http\Controllers;

use App\Enums\ReportTypeEnum;
use App\Exports\StudentOverviewExport;
use App\Exports\StudentResultsExport;
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
use App\Models\YearSemester;
use App\Models\YearSemesterStudent;
use App\Queries\StudentQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

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

        $results = Result::query()
            ->join('subjects', 'results.subjects_id', '=', 'subjects.id')
            ->join('students', 'results.students_id', '=', 'students.id')
            ->join('yearsemester', 'results.yearsemester_id', '=', 'yearsemester.id')
            ->join('studystatuses', 'students.studystatuses_id', '=', 'studystatuses.id')
            ->select([
                'results.id', 'students.name as nameStd', 'students.code as code', 'students.bonus',
                'students.site_no as site_no', 'studystatuses.name as status','results.subjects_id',
                'results.bonus', 'written', 'applied', 'kpis',
                'subjects.name as subject', 'subjects.max_written',
                'subjects.max_kpis', 'subjects.max_applied',
                'yearsemester.year as year', 'yearsemester.semester as semester', 'grade'
            ])->selectRaw('written + applied + kpis + results.bonus as total')
            ->selectRaw('students.bonus as remaining_bonus')->get();

        $students = Student::with(['group', 'department', 'specialize', 'result.subject'])
//            ->join('yearsemester_student', 'students.id', '=', 'yearsemester_student.student_id')
            ->where('students.group_id', $groupId)
            ->where('students.department_id', $departmentId)
            ->where('students.specialize_id', $specializeId);
//            ->where('yearsemester_student.yearsemester_id', $year->id);
        if ($statusId !== 'all') {
            $students->where('studystatuses_id', $statusId);
        }



        switch ((int)$data['report_type']) {
            case 1:
                $view = 'export_students';
                $exportClass = new StudentResultsExport($results, $groupId, $departmentId, $specializeId, $year, $statusId);
                break;
            case 2:
                $view = 'students_overview';

                $gradeCounts = [];
                $countAcceptedStudents = 0;
                $countGoodStudents = 0;
                $countVeryGoodStudents = 0;
                $countExcellentStudents = 0;
                $countWithOneSubject = 0;
                $countWithTwoSubject = 0;
                $absentStudents = 0;
                $failedStudents = 0;

                foreach ($students->get() as $student){
                    $gradesString = $this->totalGrade($student->id);
                    $overallGrade = implode(', ', $gradesString);


                    if (isset($gradeCounts[$overallGrade])) {
                        $gradeCounts[$overallGrade]++;
                    } else {
                        $gradeCounts[$overallGrade] = 1;
                    }


                    $enrolledStudentsCount = $students->get()->count();

                    if ($overallGrade !== null && $overallGrade === 'مقبول') {
                        $countAcceptedStudents++;
                    } elseif($overallGrade !== null && $overallGrade === 'جيد') {
                        $countGoodStudents++;
                    }elseif($overallGrade !== null && $overallGrade === 'جيد جدا') {
                        $countVeryGoodStudents++;
                    }elseif($overallGrade !== null && $overallGrade === 'ممتاز') {
                        $countExcellentStudents++;
                    }elseif($overallGrade !== null && $overallGrade === 'مادة') {
                        $countWithOneSubject++;
                    }
                    elseif($overallGrade !== null && ($overallGrade === 'راسب') ) {
                        $failedStudents++;
                    } elseif($overallGrade !== null && $overallGrade === 'غائب') {
                        $absentStudents++;
                    } elseif($overallGrade !== null && $overallGrade === 'مادتين') {
                        $countWithTwoSubject++;
                    }


                }

                $succeededStudents = $countAcceptedStudents + $countGoodStudents + $countVeryGoodStudents + $countExcellentStudents + $countWithOneSubject + $countWithTwoSubject ;
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
                $exportClass = new StudentOverviewExport($groupId, $departmentId, $specializeId, $year, $statusId , $overview);
                break;
            case 3:
                $view = 'students_statistics';
                break;
            default:
                return abort(400, 'Unsupported report type');
        }
        $group = Group::find($groupId);
        $department = Department::find($departmentId);
        $specialize = Specialize::find($specializeId);
        $status = Studystatus::find($statusId);
        $subjectNames = Subject::whereHas('groupDepartmentSpecialize', function ($query) use ($groupId, $departmentId, $specializeId) {
            $query->where('group_id', $groupId)
                ->where('department_id', $departmentId)
                ->where('specialize_id', $specializeId);
        })->pluck('name');
        $subjectDistributions = Subject::whereHas('groupDepartmentSpecialize', function ($query) use ($groupId, $departmentId, $specializeId) {
            $query->where('group_id', $groupId)
                ->where('department_id', $departmentId)
                ->where('specialize_id', $specializeId);
        })->get(['max_written', 'max_kpis', 'max_applied']);


        if($data['report_type']  == 1){
            return Excel::download($exportClass, 'filtered_results.xlsx');
        }elseif ($data['report_type'] == 2){
            $export = new StudentOverviewExport($groupId, $departmentId, $specializeId, $year, $statusId, $overview);
            return Excel::download($export, 'test_export.xlsx');
//            return Excel::download($exportClass, 'students_overview.xlsx');
        }elseif ($data['report_type'] == 3){
            return Excel::download($exportClass, 'students_statistics.xlsx');
        }else{
            return 'Undefined Report Type';
        }

    }



}
