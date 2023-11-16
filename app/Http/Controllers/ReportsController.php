<?php

namespace App\Http\Controllers;

use App\Helpers\General;
use App\Helpers\SubjectTrait;
use App\Models\Department;
use App\Models\Group;
use App\Models\GroupDepartmentSpecialize;
use App\Models\Result;
use App\Models\Specialize;
use App\Models\Studystatus;
use App\Models\Subject;
use App\Models\YearSemester;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    use SubjectTrait,General;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chooseReports(Request $request)
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
                'yearsemester_id' => 'required|array|between:1,2',
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
            //$data['studystatuses_id'] = $request->status_id;
            $data['yearsemester_id'] = $request->yearsemester_id[0];
        } else {
            $data['studystatuses_id'] = 'all';
            $data['yearsemester_id'] = $year_semester->last()->id;
        }
        return view('Dashboard.Reports.index',compact('groups','data','departments','specializes'
           ,'year_semester','subjects','status'));
    }

    public function reports()
    {
//        dd($this->totalGrade(13));
      //dd($this->calculateTotal(22,20));
        dd ($this->test2(1));
    }
}
