<?php

namespace App\Http\Controllers;

use App\Enums\ReportTypeEnum;
use App\Exports\StudentOverviewExport;
use App\Exports\StudentResultsExport;
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
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class StudentsReportsController extends Controller
{
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
        $year = YearSemester::all();
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
            $data['yearsemester_id'] = $year->last()->id;
        }
        return view('Dashboard.students-reports.filter' , compact('subjects', 'groups', 'status', 'departments', 'specializes', 'year', 'data'));
    }

    public function dataTableResultsStudents(Request $request)
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

        return Excel::download(new StudentResultsExport($results , $groupId ,  $departmentId , $specializeId ,  $year ,  $statusId), 'filtered_results.xlsx');
    }



}
