<?php

namespace App\Http\Controllers;

use App\Helpers\SubjectTrait;
use App\Models\GroupDepartmentSpecialize;
use App\Models\BonusDegree;
use App\Models\Result;
use App\Models\Studystatus;
use App\Models\ResultTransferStudent;
use App\Models\Specialize;
use App\Models\Student;
use App\Models\Group;
use App\Models\Department;
use App\Models\Trace;
use App\Models\YearSemester;
use App\Models\Subject;
use App\Models\YearSemesterStudent;
use Illuminate\Http\Request;
use  App\Helpers\General;
use App\Imports\ImportResult;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use function Symfony\Component\String\s;
use Illuminate\Support\Facades\Validator;

class ResultController extends Controller
{
    use SubjectTrait, General;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function tableResults(Request $request)
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
        $columns = [[
                ['col' => 1, 'row' => 2, 'text' => '#'],
                ['col' => 1, 'row' => 2, 'text' => 'اسم الطالب'],
                ['col' => 1, 'row' => 2, 'text' => 'كود الطالب'],
                ['col' => 1, 'row' => 2, 'text' => 'رقم جلوس الطالب'],
                ['col' => 1, 'row' => 2, 'text' => 'حالة الطالب'],
                ['col' => 1, 'row' => 2, 'text' => 'الباقي من درجات الرأفه'],
                ['col' => 1, 'row' => 2, 'text' => 'مجموع درجات الرأفه'],
            ]];
        $subjects_names = [];
        foreach ($subjects as $subject) {
            $subjects_names[] = $subject['text'];
            $columns[1][] = ['col' => 1, 'row' => 1, 'text' => 'التحريري'];
            $columns[1][] = ['col' => 1, 'row' => 1, 'text' => 'التطبيقي'];
            $columns[1][] = ['col' => 1, 'row' => 1, 'text' => 'اعمال السنة'];
            $columns[1][] = ['col' => 1, 'row' => 1, 'text' => 'درجات الرافة'];
            $columns[1][] = ['col' => 1, 'row' => 1, 'text' => 'الدرجة الكلية'];
            $columns[1][] = ['col' => 1, 'row' => 1, 'text' => 'التقدير'];
        }
        $columns[0] = array_merge($columns[0], $subjects);
        return view('Dashboard.Result.tableResults', compact('subjects', 'groups', 'status', 'columns',
            'subjects_names', 'departments', 'specializes', 'year_semester', 'data'));
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
            'yearsemester_id' => 'required|array',
            'yearsemester_id.*' => 'required|exists:yearsemester,id',
            'studystatuses_id' => 'required|in:1,2,3,all',
        ]);
        if ($validated->fails()) {
            return response(["draw" => 1,
                "recordsTotal" => 0,
                "recordsFiltered" => 0, 'data' => []], 200);
        }
        $data = $validated->validate();
        $students = $this->allResultsArray($data);
        return response(["draw" => 1,
            "recordsTotal" => count($students),
            "recordsFiltered" => count($students), 'data' => $students], 200);
    }


    public function getResults(Request $request)
    {
        return view('Dashboard.Result.index');
    }

    public function uploadResults()
    {
        $subjects = Subject::paginate();
        $groups = Group::whereNot('name', 'خريجين')->get();
        $departments = Department::paginate();
        $specializes = Specialize::paginate();
        $years = YearSemester::select('year')->distinct()->get();
        $semesters = YearSemester::select('semester')->distinct()->get();

        return view('Dashboard.Result.uploadResult', compact('subjects', 'specializes', 'groups', 'departments', 'years', 'semesters'));
    }

    public function storeResults(Request $request)
    {
        $validated = $request->validate([
            'subjects_id' => 'required|exists:subjects,id',
            'year' => [
                'required', function ($attribute, $value, $fail) use ($request) {
                    if (!YearSemester::where('year', $value)->where('semester', $request->semester)->exists()) {
                        $fail('الفصل الدراسي مع هذه السنه غير موجود');
                    }
                }
            ],
            'semester' => 'required|string|min:3|max:8|in:دور تاني,اول,ثاني',
            'file' => 'required|file|mimes:csv,xlsx,xls'
        ]);

        $id = YearSemester::where('year', $request->year)->where('semester', $request->semester)->first()->id;
//        try {
            Excel::import(new ImportResult($request->subjects_id, $id), $request->file);
            return redirect()->route('getResults')->with('success', 'تم اضافة المقرر بنجاح');
//        } catch (\Exception $e) {
//            return redirect()->route('dashboard')->with('errors', 'برجاء المحاولة مره اخري');
//        }
    }

    public function showResults()
    {
        $results = Result::where('students_id', 'students.id')->select('students_id', 'yearsemester_id', 'subjects_id', 'written', 'kpis')->get();
        return view('Dashboard.Result.show', compact('results'));
    }


    public function createYear()
    {
        $year = YearSemester::orderBy('year', 'DESC')->orderBy('semester', 'DESC')->first();
        if ($year->semester == 'اول') {
            $nextYear['year'] = $year->year;
            $nextYear['semester'] = 'ثاني';

        } elseif ($year->semester == 'ثاني') {
            $nextYear['year'] = $year->year;
            $nextYear['semester'] = 'دور تاني';

        } else {
            $lastYear = explode('/', $year->year)[1];
            $nextYear['year'] = $lastYear . '/' . ($lastYear + 1);
            $nextYear['semester'] = 'اول';
        }
        return view('Dashboard.Result.createYear', compact('nextYear'));
    }

    public function storeYear(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|string|min:9|max:9',
            'semester' => 'required|string|min:3|max:8|in:اول,ثاني,دور تاني'
        ]);
        try {

            YearSemester::create([
                'year' => $request->year,
                'semester' => $request->semester,
            ]);

            return redirect()->route('createYear')->with('success', 'تم اضافة السنة الدراسية بنجاح');
        } catch (\Exception $ex) {
            return redirect()->route('dashboard')->with('errors', 'برجاء المحاولة مره اخري');
        }
    }

    public function TransferStudentResults()
    {
        $transfer_students = Student::where('type_std', 'محول')->selectionTransferStudents()->get();
        $subjects = Subject::all();
        $groups = Group::whereNot('name', 'خريجين')->get();
        $departments = Department::all();

        return view('Dashboard.Result.resulTransferStudents', compact('subjects', 'groups',
            'departments', 'transfer_students'));
    }

    public function getTransferStudentResults()
    {
        return view('Dashboard.Result.showTransferResult');

    }

    public function storeTransferStudentResults(Request $request)
    {
        $request->validate([
            'year' => 'required|max:4',
            'students_id' => 'required|exists:students,id',
            'subjects' => 'required|array|exists:subjects,id',
            'degree' => 'required|array',  //greater than total
            'degree.*' => ['required', 'numeric', function ($attr, $value, $fail) {
                if (!$value) {
                    $fail('لايمكن ان تكون درجة المادة فارغة');
                }
            }],
            'subjects.*' => ['required', function ($attr, $value, $fail) {
                if (!$value) {
                    $fail('هذا المقرر الدراسي غير موجود');
                }
            }],
        ]);
        try {
            foreach ($request->subjects as $key => $item) {
                $input['year'] = $request->year;
                $input['students_id'] = $request->students_id;
                $input['subjects_id'] = $key;
                $input['degree'] = $request->degree[$key];
                ResultTransferStudent::create($input);
            }
            return redirect()->route('getTransferStudentResults')->with('success', 'تم اضافة النتيجه بنجاح');
        } catch (\Exception $exception) {
            return redirect()->back()->with('errors', 'برجاء المحاولة مره اخري');
        }
    }

    public function addBonus()
    {
        return view('Dashboard.Result.addBonus');
    }

    public function upgradeBonus()
    {
        $next = $this->getNextAction();
        if ($next['action'] != 'add_bonus' and $next['action'] != 'second_attempt_add_bonus') {
            return redirect()->back()->withErrors('لقد تم رفع درجات التيسير من قبل');
        }
        $year_semester_ids = YearSemester::where('yearsemester.year', '=', $next['year'])
            ->select('id', 'semester')->get()->pluck('id', 'semester')->toArray();
        if (count($year_semester_ids) != 3) {
            return redirect()->back()->withErrors('برجاء انشاء السنه الدراسية');
        }
        if ($next['semester'] == 'ثاني') {
            $students = Student::with(['result' => function ($query) use ($year_semester_ids) {
                $query->whereIn('yearsemester_id', $year_semester_ids)->with('subject.groupDepartmentSpecialize');
            }]);
        } else {
            $students = Student::where('group_id', 4)->with(['result' => function ($query) use ($year_semester_ids) {
                $query->where('yearsemester_id', $year_semester_ids['دور تاني'])
                    ->with('subject.groupDepartmentSpecialize');
            }])->whereHas('result', function ($query) use ($year_semester_ids) {
                $query->where('yearsemester_id', $year_semester_ids['دور تاني']);
            });
        }
        $students = $students->get()->transform(function ($value) {
            if ($value->result->isNotEmpty())
                $value->setRelation('result', $value->result->sortBy([
                    ['subject.groupDepartmentSpecialize.group_id', 'asc'],
                    function ($value1, $value2) {
                        $total1 = $value1->written + $value1->kpis + $value1->applied;
                        $total2 = $value2->written + $value2->kpis + $value2->applied;
                        return ($total1) > ($total2);
                    }
                ])->values());
            return $value;
        });
        $flag = false;
        $message = '';

        try {
            DB::transaction(function () use ($next, $year_semester_ids, $students, &$flag, &$message) {
                foreach ($students as $student) {
                    if ($student->result->count() < 14 and $next['semester'] == 'ثاني') {
                        $flag = true;
                        $message = 'خطا يجب رفع علي الاقل 14 مادة';
                        abort(500);
                    }
                    elseif ($student->result->count() > 2 and $next['semester'] == 'دور تاني') {
                        $flag = true;
                        $message = 'خطا يجب رفع بحد اقصي 2 مادة';
                        abort(500);
                    }
                    if ($student->bonus <= 0) {
                        continue;
                    }
                    foreach ($student->result as $result) {
                      if($result->grade != 'راسب تحريرى'){
                        $bonus = $this->calculateBonus($result->written + $result->kpis + $result->applied);
                        $student->bonus -= ($student->bonus >= $bonus) ? $bonus : 0;
                        $student->save();
                    }
                        $result->bonus = $bonus;
                        $result->grade = $this->grade($result->subjects_id, $result->written, $result->kpis, $result->applied, $bonus);
                        $result->save();
                        if ($student->bonus <= 0) {
                            break;
                        }
                    }
                }
                if ($next['action'] == 'add_bonus') {
                    Trace::create([
                        'yearsemester_id' => $year_semester_ids['ثاني'],
                        'action' => 'add_bonus',
                    ]);
                } elseif ($next['action'] == 'second_attempt_add_bonus') {
                    Trace::create([
                        'yearsemester_id' => $year_semester_ids['دور تاني'],
                        'action' => 'second_attempt_add_bonus',
                    ]);
                }
            });
            return redirect()->back()->with(['success' => 'تم اضافة درجات التيسير بنجاح']);
        } catch (\Exception $e) {
            dd($e);
            if ($flag) {
                return redirect()->back()->withErrors(['error' => $message]);
            }
            return redirect()->back()->withErrors(['error' => 'برجاء المحاولة مره اخري']);
        }
    }

    public function upgradeGroups(Request $request)
    {
        $next = $this->getNextAction();
        if ($next['action'] != 'up_level' and $next['action'] != 'second_attempt_up_level') {
            return redirect()->back()->withErrors('برجاء احتساب درجات التيسير اولا');
        }
        $year_semester_ids = YearSemester::where('year', '=', $next['year'])
            ->select('id', 'semester')->pluck('id', 'semester')->toArray();
        if (count($year_semester_ids) != 3) {
            return redirect()->back()->withErrors('برجاء انشاء السنه الدراسية');
        }
        if ($next['action'] == 'up_level') {

            $students = Student::where('group_id', [1, 2, 3, 4])->with(['yearSemesterStudent',
                'result' => function ($query) use ($year_semester_ids) {
                    $query->where('yearsemester_id', $year_semester_ids)->with('subject');
                }])->get();

            $flag = false;
            $message = '';

            try {
                DB::transaction(function () use ($year_semester_ids, $students, &$flag, &$message,$next) {
                    foreach ($students as $student) {
                        if ($student->load('result')->result->count() < 14) {
                            $flag = true;
                            $message = 'خطا يجب رفع علي الاقل 14 مادة';
                            //abort(500);
                        }
                        YearSemesterStudent::create([
                            'student_id' => $student->id,
                            'yearsemester_id' => $year_semester_ids['ثاني'],
                            'group_id' => $student->group_id,
                            'department_id' => $student->department_id,
                            'specialize_id' => $student->specialize_id,
                            'studystatus_id' => $student->studystatuses_id,
                            'site_no' => $student->site_no,
                        ]);
                        $count = $student->result->whereIn('grade', ['ضعيف', 'ضعيف جدا', 'راسب تحريرى', 'غياب'])->count();
                        if ($count < 3) {
                            if ($student->group_id == 4) {
                                if ($student->training != 'راسب' and $student->military != 'لم اجتاز' and $count == 0) {
                                    $student->group_id++;
                                    $years = explode('/', $student->year);
                                    if (count($years) == 2) {
                                        $startYear = intval($years[0]);
                                        $endYear = intval($years[1]);
                                        $startYear++;
                                        $endYear++;
                                        $newYear = $startYear . '/' . $endYear;
                                        $student->year = $newYear;

                                    }
                                }
                            } elseif ($student->group_id == 3) {
                                if ($student->training_third_group == 'ناجح' or $count < 2) {
                                    $student->group_id++;
                                    $years = explode('/', $student->year);
                                    if (count($years) == 2) {
                                        $startYear = intval($years[0]);
                                        $endYear = intval($years[1]);
                                        $startYear++;
                                        $endYear++;
                                        $newYear = $startYear . '/' . $endYear;
                                        $student->year = $newYear;

                                    }
                                }
                            } else {
                                $student->group_id++;
                                $years = explode('/', $student->year);
                                if (count($years) == 2) {
                                    $startYear = intval($years[0]);
                                    $endYear = intval($years[1]);
                                    $startYear++;
                                    $endYear++;
                                    $newYear = $startYear . '/' . $endYear;
                                    $student->year = $newYear;

                                }
                            }
                            $student->studystatuses_id = 1;
                            $bonuGgroup = BonusDegree::first()->getOriginal()['degree_group' . $student->group_id];
                            $student->bonus += $bonuGgroup;
                        } else {
                            if ($student->studystatuses_id == 1) {
                                $student->studystatuses_id = 2;
                            } elseif ($student->studystatuses_id == 2) {
                                if ($student->group_id == 1) {
                                    $student->classfication = 'مفصول';
                                } else {
                                    $student->studystatuses_id = 3;
                                    $student->classfication = 'مستمر في الدراسة';
                                }
                            } elseif ($student->studystatuses_id == 3) {
                                if ($student->group_id == 2) {
                                    $student->classfication = 'مفصول';
                                } elseif ($student->group_id == 3) {
                                    $ex_count = $student->yearSemesterStudent->where('group_id', 3)
                                        ->where('studystatus_id', 3)->count();
                                    if ($ex_count >= 2) {
                                        $student->classfication = 'مفصول';
                                    }
                                } else {
                                    $student->studystatuses_id = 3;
                                    $student->classfication = 'مستمر في الدراسة';
                                }
                            }
                        }
                        $student->save();
                    }
                    Trace::create([
                        'yearsemester_id' => $year_semester_ids['ثاني'],
                        'action' => 'up_level',
                    ]);
                });
                return redirect()->back()->with(['success' => ' تم تحديث الفرق بنجاح']);

            } catch (\Exception $e) {
                if ($flag) {
                    return redirect()->back()->withErrors(['error' => $message]);
                }
                return redirect()->back()->withErrors(['error' => 'برجاء المحاولة مره اخري']);
            }
        } else {
            $students = Student::where('group_id', 4)->with(['yearSemesterStudent',
                'result' => function ($query) use ($year_semester_ids) {
                    $query->where('yearsemester_id', end($year_semester_ids))->with('subject');
                }])->whereHas('result', function ($query) use ($year_semester_ids) {
                $query->where('yearsemester_id', $year_semester_ids['دور تاني']);
            })->get();
            $flag = false;
            $message = '';
            try {
                DB::transaction(function () use ($year_semester_ids, $students, &$flag, &$message) {
                    foreach ($students as $student) {
                        if ($student->result->count() > 2) {
                            $flag = true;
                            $message = 'خطا يجب رفع بحد اقصي 2 مادة';
                            abort(500);
                        }
                        YearSemesterStudent::create([
                            'student_id' => $student->id,
                            'yearsemester_id' => $year_semester_ids['دور تاني'],
                            'group_id' => $student->group_id,
                            'department_id' => $student->department_id,
                            'specialize_id' => $student->specialize_id,
                            'studystatus_id' => $student->studystatuses_id,
                            'site_no' => $student->site_no,
                        ]);
                        $success = !$student->result->contains(function ($value) {
                            return in_array($value->grade, ['ضعيف', 'ضعيف جدا', 'راسب تحريرى', 'غياب']);
                        });
                        if ($success) {
                            if ($student->training == 'ناجح') {
                                $student->group_id++;
                                $years = explode('/', $student->year);
                                if (count($years) == 2) {
                                    $startYear = intval($years[0]);
                                    $endYear = intval($years[1]);
                                    $startYear++;
                                    $endYear++;
                                    $newYear = $startYear . '/' . $endYear;
                                    $student->year = $newYear;
                                    dd($student->year);

                                }
                            }
                            $student->studystatuses_id = 1;
                        } else {
                            if ($student->studystatuses_id == 1) {
                                $student->studystatuses_id = 2;
                            } elseif ($student->studystatuses_id == 2) {
                                $student->studystatuses_id = 3;
                                $student->classfication = 'مستمر في الدراسة';
                            } elseif ($student->studystatuses_id == 3) {
                                $student->studystatuses_id = 3;
                                $student->classfication = 'مستمر في الدراسة';
                            }
                        }
                        $student->save();
                    }
                    Trace::create([
                        'yearsemester_id' => $year_semester_ids['دور تاني'],
                        'action' => 'second_attempt_up_level',
                    ]);
                });
                return redirect()->back()->with(['success' => ' تم تحديث الفرق الدور التانى بنجاح']);
            } catch (\Exception $e) {
                if ($flag) {
                    return redirect()->back()->withErrors(['error' => $message]);
                }
                return redirect()->back()->withErrors(['error' => 'برجاء المحاولة مره اخري']);
            }
        }
    }

}

