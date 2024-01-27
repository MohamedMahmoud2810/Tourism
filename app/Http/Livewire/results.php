<?php

namespace App\Http\Livewire;

use App\Helpers\General;
use App\Helpers\SubjectTrait;
use App\Models\Result;
use App\Models\Student;
use App\Models\Studystatus;
use App\Models\Subject;
use App\Models\YearSemester;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button,
    Column,
    Exportable,
    Footer,
    Header,
    PowerGrid,
    PowerGridComponent,
    PowerGridEloquent
};

final class results extends PowerGridComponent
{

    public int $name, $code, $subjects_id, $year, $semester;
   public array $written, $kpis, $applied;
    use ActionButton;
    use General, SubjectTrait;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput()->showToggleColumns(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource()
    {
        return Result::query()
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
    }


    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('nameStd')
            ->addColumn('year')
            ->addColumn('semester')
            ->addColumn('name', function (Result $model) {
                return strtolower(e($model->name));
            })
            ->addColumn('code')
            ->addColumn('sit_no')
            ->addColumn('written')
            ->addColumn('subject')
            ->addColumn('kpis')
            ->addColumn('applied')
            ->addColumn('bonus')
            ->addColumn('remaining_bonus')
            ->addColumn('grade')
            ->addColumn('remaining_bonus');

    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id'),
            Column::make('اسم الطالب', 'nameStd')
                ->sortable()
                ->searchable(),
            Column::make('كود الطالب', 'code')
                ->sortable()
                ->searchable(),
            Column::make('رقم جلوس الطالب', 'site_no')
                ->sortable()
                ->searchable()->makeInputText('site_no', false),
            Column::make('حالة الطالب', 'status')
                ->makeInputSelect(Studystatus::select('name')->distinct()->get(), 'name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('السنة', 'year')
                ->makeInputSelect(YearSemester::select('year')->distinct()->get(), 'year', 'year')
                ->sortable()
                ->searchable(),
            Column::make('الفصل الدراسي', 'semester')
                ->makeInputSelect(YearSemester::select('semester')->distinct()->get(), 'semester', 'semester')
                ->sortable()
                ->searchable(),
            Column::make('المادة / المقرر', 'subject')->searchable()
                ->makeInputSelect(Result::with('subject')->get()->transform(function ($value) {
                    return ['id' => $value->subject->id, 'name' => $value->subject->name];
                })->unique('id'), 'name', 'subjects_id')->sortable(),

            Column::make('التحريري', 'written')->editOnClick(),
            Column::make('التطبيقي', 'applied')->editOnClick(),
            Column::make('اعمال السنة', 'kpis')->editOnClick(),
            Column::make('درجات الرافة', 'bonus'),
            Column::make('الباقي من الرافة', 'remaining_bonus'),
            Column::make(' الدرجة الكلية ', 'total'),
            Column::make(__(' التقدير '), 'grade')
                ->makeInputSelect(Result::select('grade')->distinct()->get(),'grade','grade'),
        ];
    }

    public bool $showErrorBag = true;
    protected function rules(): array
    {
        return [
            'written.*' => 'nullable',
            'applied.*' => 'required',
            'kpis.*' => 'required',
        ];
    }

    protected array $messages = [
        'required' => 'هذا الحقل مطلوب',
        'integer' => 'يجب ان تكون رقما'
    ];
    // public function onUpdatedEditable(string $id, string $field, string $value): void
    // {

    //     $subject = Result::with('subject')->find($id)->subject->getOriginal();
    //     $this->validate(
    //         [
    //             'written.*' => 'integer|between:0,' . $subject['max_written'],
    //             'applied.*' => 'integer|between:0,' . $subject['max_applied'],
    //             'kpis.*' => 'integer|between:0,' . $subject['max_kpis'],
    //         ]
    //     );
    //     $result = Result::query()->find($id)->getOriginal();
    //     $result_student = Result::query()->with('student')->find($id);
    //     $bonus = $result['bonus'];
    //     $result[$field] = $value;
    //     if ($result_student->student->bonus > 0) {
    //         $bonus = $this->calculateBonus((int)$result['written'] + $result['kpis'] + $result['applied']);
    //         if ($result_student->student->bonus >= ($bonus - $result['bonus'])) {
    //             $bonus_diff = $result['bonus'] - $bonus;
    //             $result_student->student->increment('bonus', $bonus_diff);
    //         } else {
    //             $bonus = $result['bonus'];
    //         }
    //     }
    //     $grade = $this->grade($result['subjects_id'],$result['written'] === '' ? null : $result['written'], $result['kpis'], $result['applied'], $bonus);

    //    // $grade = $this->grade($result['subjects_id'], $result['written'], $result['kpis'], $result['applied'], $bonus);
    //     /*** update student bonus and remove bonus if not needed**/
    //     Result::find($id)->update([
    //         $field => $value,
    //         'bonus' => $bonus,
    //         'grade' => $grade,
    //     ]);
    // }

    public function onUpdatedEditable(string $id, string $field, string $value): void
    {

        $subject = Result::with('subject')->find($id)->subject->getOriginal();
        $this->validate(
            [
                'written.*' => 'integer|between:0,' . $subject['max_written'],
                'applied.*' => 'integer|between:0,' . $subject['max_applied'],
                'kpis.*' => 'integer|between:0,' . $subject['max_kpis'],
            ]
        );
        $result = Result::find($id)->getOriginal();
        $result_student = Result::with('student')->find($id);
        $bonus = $result['bonus'];
        $result[$field] = $value;
        if ($result_student->student->bonus > 0) {
            if($result['grade'] != 'راسب تحريرى'){
            $bonus = $this->calculateBonus( (int)$result['written'] + $result['kpis'] + $result['applied']);
            if ($result_student->student->bonus >= ($bonus - $result['bonus'])) {
                $bonus_diff = $result['bonus'] - $bonus;
                $result_student->student->increment('bonus', $bonus_diff);}
            }
             else {
                $bonus = $result['bonus'];
            }
        }

        $grade = $this->grade($result['subjects_id'],$result['written'] === '' ? null : $result['written'], $result['kpis'], $result['applied'], $bonus);
        /*** update student bonus and remove bonus if not needed**/
        if ($field=="written" and $value==null){
            $value=null;
            Result::find($id)->update([
                'kpis' => $result['kpis'],
                'bonus' => $bonus,
                'grade' => $grade,
            ]);
            Result::find($id)->update([
                'applied' => $result['applied'],
                'bonus' => $bonus,
                'grade' => $grade,
            ]);
            Result::find($id)->update([
                $field => $value,
                'bonus' => $bonus,
                'grade' => $grade,
            ]);
        }
        else
        Result::find($id)->update([
            $field => $value,
            'bonus' => $bonus,
            'grade' => $grade,
        ]);
    }


}
