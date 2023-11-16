<?php

namespace App\Http\Livewire;

use App\Models\Result;
use App\Models\ResultTransferStudent;
use App\Helpers\SubjectTrait;
use Illuminate\Support\Carbon;
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
    PowerGridEloquent};

final class tranferresult extends PowerGridComponent
{
    public $degree;
    use ActionButton, SubjectTrait;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }


    public function datasource()
    {
        return ResultTransferStudent::join('subjects', 'result_transfer_students.subjects_id', '=', 'subjects.id')
            ->join('students', 'result_transfer_students.students_id', '=', 'students.id')
            ->join('departments', 'students.department_id', '=', 'departments.id')
            ->join('groups', 'students.group_id', '=', 'groups.id')
            ->join('specializes', 'students.specialize_id', '=', 'specializes.id')
            ->select([
                'result_transfer_students.id', 'result_transfer_students.year as year', 'result_transfer_students.degree',
                'grade', 'students.name as nameStd', 'students.code as code', 'students.site_no as site_no',
                'groups.name as group', 'departments.name as department', 'specializes.name as specialize',
                'subjects.name as subject'
            ]);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('year')
            ->addColumn('year_lower', function (ResultTransferStudent $model) {
                return strtolower(e($model->year));
            })
            ->addColumn('students_id')
            ->addColumn('group')
            ->addColumn('department')
            ->addColumn('specialize')
            ->addColumn('subjects_id')
            ->addColumn('degree')
            ->addColumn('grade');
    }

    public function columns(): array
    {
        return [
            Column::make('كود الطالب', 'code')
                ->searchable()
                ->makeInputText(),
            Column::make('اسم الطالب', 'nameStd')
                ->searchable()
                ->makeInputText(),
            Column::make('الفرقه الدراسية', 'group')
                ->searchable()
                ->makeInputText(),
            Column::make('الشعبة', 'department')
                ->searchable()
                ->makeInputText(),
            Column::make('التخصص', 'specialize')
                ->searchable()
                ->makeInputText(),

            Column::make('المقرر الدراسي', 'subject')
                ->makeInputSelect(ResultTransferStudent::with('subject')->get()->transform(function ($value) {
                    return ['id' => $value->subject->id, 'name' => $value->subject->name];
                })->unique('id'), 'name', 'subjects_id')->sortable(),

            Column::make('السنه', 'year')
                ->sortable()
                ->searchable()
                ->makeInputText(),
            Column::make('الدرجة الكلية', 'degree')->editOnClick(),
            Column::make('التقدير', 'grade'),
        ];
    }

    public bool $showErrorBag = true;
    protected array $rules = [
        'degree.*' => ['required', 'integer', 'between:0,100'],
    ];

    public function onUpdatedEditable(string $id, string $field, string $value): void
    {
        $this->validate();
        $result = ResultTransferStudent::find($id)->getOriginal();
        $grade = $this->grade($result['subjects_id'], $value, 0, 0);
        ResultTransferStudent::find($id)->update([
            $field => $value,
            'grade' => $grade,
        ]);
    }

}
