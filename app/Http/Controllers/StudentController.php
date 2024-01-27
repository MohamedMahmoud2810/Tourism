<?php

namespace App\Http\Controllers;

use  App\Helpers\General;
use App\Imports\UpdateStudentsImport;
use App\Models\GroupDepartmentSpecialize;
use App\Models\Specialize;
use App\Models\Studystatus;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Subject;
use App\Models\Group;
use App\Models\YearSemester;
use App\Imports\ImportStudent;
use App\Imports\SeatNumberImport;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    use General;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Students()
    {
        return view('Dashboard.Student.index');
    }

    public function getDepartment(Request $request)
    {
        $data['departments'] = GroupDepartmentSpecialize::where('group_id', $request->group_id)
            ->with('department')->get()->transform(function ($value) {
                $depart = $value->department()->first();
                return ['id' => $depart->id, 'name' => $depart->name];
            })->unique('id');
        return response()->json($data);
    }

    public function getSpecializes(Request $request)
    {
        $data['specializes'] = GroupDepartmentSpecialize::where('group_id', $request->group_id)
            ->where('department_id', $request->department_id)
            ->with('specialize')->get()->transform(function ($value) {
                $spec = $value->specialize()->first();
                return ['id' => $spec->id, 'name' => $spec->name];
            })->unique('id');
        return response()->json($data);
    }

    public function getsubject(Request $request)
    {
        if ($request->term == 'دور تاني') {
            $request->request->remove('term');
        }
        if (isset($request->term)) {
            $data['subjects'] = GroupDepartmentSpecialize::where('group_id', $request->group_id)
                ->where('department_id', $request->department_id)
                ->where('specialize_id', $request->specialize_id)
                ->with('subject')->get()->transform(function ($value) use ($request) {
                    $subjects = $value->subject()->where('term', $request->term)->get();
                    $val = [];
                    foreach ($subjects as $subject) {
                        $val[] = ['id' => $subject->id, 'name' => $subject->name];
                    }
                    return $val;
                })[0];
        } else {
            $data['subjects'] = GroupDepartmentSpecialize::where('group_id', $request->group_id)
                ->where('department_id', $request->department_id)
                ->where('specialize_id', $request->specialize_id)
                ->with('subject')->get()->transform(function ($value) {
                    $subjects = $value->subject()->get();
                    $val = [];
                    foreach ($subjects as $subject) {
                        $val[] = ['id' => $subject->id, 'name' => $subject->name];
                    }
                    return $val;
                })[0];
        }
        return response()->json($data);
    }

    public function CreateStudents()
    {
        $studystatuses = Studystatus::paginate();
        $specializes = Specialize::all();
        $groups = Group::whereNot('name', 'خريجين')->get();
        $departments = Department::paginate();
        $years = YearSemester::select('year')->distinct()->get();

        return view('Dashboard.Student.create', compact('groups', 'specializes', 'departments', 'studystatuses','years'));
    }

    public function storeStudents(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'studystatuses_id' => 'required|exists:studystatuses,id',
            'type_std' => 'required|string|between:3,8',
            'departments_id' => 'required|exists:departments,id',
            'specialize_id' => 'required|exists:specializes,id',
            'year' => 'required|string|min:9|max:9',
            'file' => 'required|file|mimes:csv,xlsx,xls'
        ]);
        $excel = Excel::import(new ImportStudent($request->departments_id, $request->specialize_id,
            $request->group_id, $request->studystatuses_id,$request->type_std,$request->year),
            $request->file);
        return redirect()->route('Students')->with('success', ('تم اضافة الطلاب بنجاح'));
    }

    public function updateStudents()
    {
        return view('Dashboard.Student.updateStudent');
    }

    public function storeUpdatedStudents(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls'
        ]);
        $excel = Excel::import(new UpdateStudentsImport(), $request->file);
        return redirect()->route('Students')->with('success', ('تم اضافة التدريب والتربية العسكرية بنجاح'));
    }


    public function updateStudentsSeatNo()
    {
        return view('Dashboard.Student.update_seatNo');
    }

    public function storeUpdatedStudentsSeatNo(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls'
        ]);
        $excel = Excel::import(new SeatNumberImport(), $request->file);
        return redirect()->route('Students')->with('success', ('تم تحديث ارقام الجلوس بنجاح'));
    }

}
