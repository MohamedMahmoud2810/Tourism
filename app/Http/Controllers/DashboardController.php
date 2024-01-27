<?php

namespace App\Http\Controllers;

use  App\Helpers\General;
use App\Models\GroupDepartmentSpecialize;
use App\Models\Result;
use App\Models\Specialize;
use App\Models\Studystatus;
use App\Models\YearSemester;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;
use App\Models\Subject;
use App\Models\Group;
use App\Models\BonusDegree;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    use General;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // public function dataTableResultsStudents(Request $request)
    // {
    //     $validated = Validator::make($request->all(), [
    //         'department_id' => 'required|exists:departments,id',
    //         'group_id' => 'required|exists:groups,id',
    //         'specialize_id' => ['required', 'exists:specializes,id',
    //             function ($attr, $value, $fail) use ($request) {
    //                 $check = GroupDepartmentSpecialize::where('specialize_id', $value);
    //                 if (!is_null($request->group_id)) {
    //                     $check->where('group_id', $request->group_id);
    //                 }
    //                 if (!is_null($request->departments_id)) {
    //                     $check->where('department_id', $request->departments_id);
    //                 }
    //                 if (!$check->exists()) {
    //                     $fail('Error');
    //                 }
    //             }],
    //         'yearsemester_id' => 'required|array',
    //         'yearsemester_id.*' => 'required|exists:yearsemester,id',
    //         'studystatuses_id' => 'required|in:1,2,3,all',
    //     ]);
    //     if ($validated->fails()) {
    //         return response(["draw" => 1,
    //             "recordsTotal" => 0,
    //             "recordsFiltered" => 0, 'data' => []], 200);
    //     }
    //     $data = $validated->validate();
    //     $students = $this->allResultsArray($data);
    //     return response(["draw" => 1,
    //         "recordsTotal" => count($students),
    //         "recordsFiltered" => count($students), 'data' => $students], 200);
    // }

    // public function test3(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'action' => 'required|string|in:edit',
    //         'data' => 'required|array|size:1',
    //         'data.*' => 'required|array',
    //         'data.*.*' => 'exclude_without:data.*.*.id|array|size:3',
    //         'data.*.*.id' => 'exclude_if:data.*.*.id,null|required|integer|exists:results,id',
    //         'data.*.*.written' => 'exclude_without:data.*.*.id|required|integer',
    //         'data.*.*.applied' => 'exclude_without:data.*.*.id|required|integer',
    //         'data.*.*.kpis' => 'exclude_without:data.*.*.id|required|integer',
    //     ]);
    //     if ($validator->fails()) {
    //         return response($validator->errors(), 400);
    //     }
    //     $data = $validator->validate()['data'];
    //     $flag = false;
    //     foreach ($data as $row => $subjects) {
    //         foreach ($subjects as $subject => $degrees) {
    //             $result = Result::find($degrees['id']);
    //             [$kpis, $written, $applied] = $this->getSubjectDegrees($result->subjects_id);
    //             if ($degrees['written'] <= $written and $degrees['written'] >= 0 and
    //                 $degrees['kpis'] <= $kpis and $degrees['kpis'] >= 0 and
    //                 $degrees['applied'] <= $applied and $degrees['applied'] >= 0) {
    //                 try {
    //                     $flag = true;
    //                     $total = $degrees['kpis'] + $degrees['applied'] + $degrees['written'];
    //                     $bonus = $this->calculateBounce($total);
    //                     DB::transaction(function () use ($result, $bonus, $degrees) {
    //                         $remaining_bonus = RemainBonus::where('student_id', $result->students_id)->first();
    //                         $remaining_bonus->remaining_bonus += $result->bonus;
    //                         $remaining_bonus->save();
    //                         $result->bonus = 0;
    //                         $result->save();
    //                         $student = Student::with('result')->find($result->students_id);
    //                         $total_bonus = $student->result->sum('bonus');
    //                         $given_bonus = $student->remainBonus->given_bonus;
    //                         if ($remaining_bonus->remaining_bonus >= $bonus) {
    //                             $remaining_bonus_new = $given_bonus - $total_bonus - $bonus;
    //                             $student->remainBonus()->update([
    //                                 'remaining_bonus' => $remaining_bonus_new
    //                             ]);
    //                             $result->bonus = $bonus;
    //                         }
    //                         $result->written = $degrees['written'];
    //                         $result->applied = $degrees['applied'];
    //                         $result->kpis = $degrees['kpis'];
    //                         $result->save();
    //                     });
    //                 } catch (\Exception $e) {
    //                     return response($e->getMessage(), 400);
    //                 }
    //             } else {
    //                 break;
    //             }
    //         }
    //     }
    //     $change = [
    //         'id' => array_key_first($data),
    //     ];
    //     if ($flag) {
    //         $student = Student::with(['result', 'remainBonus'])->find($result->students_id);
    //         $change['remaining_bonus'] = $student->remainBonus->remaining_bonus;
    //         $change['total_bonus'] = $student->result->sum('bonus');
    //         $change[array_key_first($data[array_key_first($data)])] = [
    //             'written' => $result->written,
    //             'applied' => $result->applied,
    //             'kpis' => $result->kpis,
    //             'bonus' => $result->bonus,
    //             'total' => $result->written + $result->applied + $result->kpis + $result->bonus,
    //             'grade' => $this->grade($result->written, $result->kpis, $result->applied, $result->bonus),
    //         ];
    //     }
    //     return response([
    //         'data' => [$change],
    //         'debug' => [],
    //     ], 200);
    // }

    public function dashboard()
    {
        $subjects = Subject::count();
        $departments = Department::count();
        $students = Student::count();
        return view('Dashboard.Dashboard', compact('subjects', 'departments', 'students'));
    }

    public function studentDestroy($id)
    {
        $std = Student::find($id);
        $std->delete();
    }

    public function Departments()
    {
        $departments = Department::all();
        return view('Dashboard.Department.index', compact('departments'));
    }

    public function CreateDepartment()
    {
        return view('Dashboard.Department.create');
    }

    public function StoreDepartment(Request $request)
    {
        $name = $this->removeArabicChar($request->name);
        $validated = $request->validate([
            'name' => 'required|string|between:3,100|unique:departments',
//            'group_id' => ['required', 'exists:groups,id', function ($att, $value, $fail) use ($name) {
//                if (Department::where('name', $name)->where('group_id', $value)->exists()) {
//                    $fail('لا بمكن تكرار القسم فى نفس الفرقه');
//                }
//            }]
        ]);
        try {
            Department::create(['name' => $name]);
            return redirect()->route('Departments')->with('success', ('تم اضافة القسم بنجاح'));
        } catch (\Exception $ex) {
            return redirect()->route('Departments')->with('errors', ('برجاء المحاولة مره اخري'));
        }
    }

    public function DestroyDepartment($id)
    {
        $department = GroupDepartmentSpecialize::find($id);
        if ($department != null) {
            $department->delete();
            return redirect()->route('Departments')->with('success', ('تم حذف الشعبة بنجاح'));
        }

    }

    ###################### Start Store Specialize ##########################
    public function CreateSpecialize()
    {
        return view('Dashboard.Department.createSpecialize');
    }

    public function storeSpecialize(Request $request)
    {
        $name = $this->removeArabicChar($request->name);
        $validated = $request->validate([
            'name' => 'required|string|between:3,100|unique:specializes',
        ]);
        try {
            Specialize::create(['name' => $name]);
            return redirect()->route('Departments')->with('success', ('تم اضافة القسم بنجاح'));
        } catch (\Exception $ex) {
            return redirect()->route('Departments')->with('errors', ('برجاء المحاولة مره اخري'));
        }
    }
    ###################### End Store Specialize ##########################

    ###################### Start Store Subjects ##########################

    public function Subjects()
    {
        $groups = Group::paginate();
        return view('Dashboard.Subjects.index', compact('groups'));
    }

    public function CreateSubjects()
    {
        $groups = Group::whereNot('name','خريجين')->get();
        return view('Dashboard.Subjects.create', compact('groups'));
    }


    public function storeSubjects(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|between:4,255',
            'max_written' => 'required|integer|min:0',
            'max_kpis' => 'required|integer|min:0',
            'max_applied' => ['required', 'integer', 'min:0',
                function ($att, $value, $fail) use ($request) {
                    $max_total = $request->max_written + $request->max_kpis + $value;
                    if ($max_total <= 0) {
                        $fail('لايمكن ان يكون المجموع الكلي للمادة بصفر');
                    }
                }],
            'code_subject' => 'required|string|between:6,8|regex:/^[^\x{0621}-\x{064A}٠-٩ ]+$/u',
            'group_id' => 'required|integer|exists:groups,id',
            'departments_id' => 'required|integer|exists:departments,id',
            'specialize_id' => ['required', 'integer', 'exists:specializes,id',
                function ($att, $value, $fail) use ($request) {
                    $gds = GroupDepartmentSpecialize::where('group_id', $request->group_id)
                        ->where('department_id', $request->departments_id)->where('specialize_id', $value);
                    if (!$gds->exists()) {
                        $fail('لا يوجد الفرقه مع الشعبة و التخصص معا');
                        return;
                    }
                    if (!$gds->first()->subject()->get()->where('code_subject', $request->code_subject)->isEmpty())
                        $fail('لا بمكن تكرار المادة فى نفس الفرقه و الشعبة و التخصص');
                }],
            'term' => 'required|string|in:ثاني,اول',
            'type_subject' => 'required|string|in:اجباري,اختياري',
        ]);
        try {
            $gds = GroupDepartmentSpecialize::where('group_id', $validated['group_id'])
                ->where('department_id', $validated['departments_id'])
                ->where('specialize_id', $validated['specialize_id'])->first();
            Subject::create([
                'name' => $this->removeArabicChar($validated['name']),
                'code_subject' => $validated['code_subject'],
                'gds_id' => $gds->id,
                'term' => $validated['term'],
                'type_subject' => $validated['type_subject'],
                'max_written' => $validated['max_written'],
                'max_kpis' => $validated['max_kpis'],
                'max_applied' => $validated['max_applied'],
            ]);
            return redirect()->route('Subjects')->with('success', ('تم اضافة المقرر بنجاح'));
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors('error', ('برجاء المحاولة مره اخري'));

        }
    }
    ###################### End Store Subjects ##########################


    ##################### start Store grDepSp ##########################
    public function createGrDepSp()
    {
        $groups = Group::whereNot('name', 'خريجين')->get();
        $departments = Department::all();
        $specializes = Specialize::all();
        return view('Dashboard.Department.createGrDepSp', compact('groups', 'departments', 'specializes'));
    }

    public function storeGrDepSp(Request $request)
    {
        $request->validate([
            'group_id' => 'required|integer|exists:groups,id',
            'department_id' => 'required|integer|exists:departments,id',
            'specialize_id' => ['required', 'integer', 'exists:specializes,id',
                function ($att, $value, $fail) use ($request) {
                    $gds = GroupDepartmentSpecialize::where('group_id', $request->group_id)
                        ->where('department_id', $request->department_id)->where('specialize_id', $value);
//                    if (!$gds->exists()) {
//                        $fail('لا يوجد الفرقه مع الشعبة و التخصص معا');
//                        return;
//                    }
                    if ($gds->exists()) {
                        $fail('لا يمكن تكرار الفرقه مع الشعبة و التخصص معا');
                    }
                }],
        ]);
        try {
            GroupDepartmentSpecialize::create([
                'group_id' => $request->group_id,
                'department_id' => $request->department_id,
                'specialize_id' => $request->specialize_id,
            ]);
            return redirect()->route('Departments')->with('success', ('تم الاضافه بنجاح'));
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors('error', ('برجاء المحاولة مره اخري'));
        }
    }

    ##################### End Store grDepSp ##########################


    ###################### Start Delete Subject ##########################
    public function DestroySubjects($id)
    {
        $subject = Subject::where('id', $id)->first();

        if ($subject != null) {
            $subject->delete();
            return redirect()->route('Subjects')->with('success', ('تم حذف المقرر بنجاح'));
        }
    }
    ###################### End Delete Subject ##########################

    // start BonusDegree function
    public function bonusDegree($id)
    {
        $degrees = BonusDegree::find($id);
        return view('Dashboard.Degree.degree', compact('degrees'));
    }

    public function StoreBonusDegree($id, Request $request)
    {
        $validated = $request->validate([
            'degree_group1' => 'required|integer|between:1,30',
            'degree_group2' => 'required|integer|between:1,30',
            'degree_group3' => 'required|integer|between:1,30',
            'degree_group4' => 'required|integer|between:1,30',
        ]);
        try {
            $degrees = BonusDegree::find($id);
            $degrees->update($request->all());

            return redirect()->route('dashboard')->with('success', ('تم تحديث درجات التيسير بنجاح'));

        } catch (\Exception $ex) {
            return redirect()->route('dashboard')->with('error', ('برجاء المحاولة مره اخري'));
        }
    }

}
