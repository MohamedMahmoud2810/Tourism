<?php

namespace App\Http\Livewire;


use App\Models\Result;
use App\Models\Subject;

use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{RuleActions};
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

final class subjects extends PowerGridComponent
{
    public $name, $term,$max_written,$max_kpis,$max_applied;
    use ActionButton;

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
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

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\Subject>
     */
    public function header(): array
    {
        return [
            Button::add('bulk-demo')
                ->caption(__('Bulk Action'))
                ->class('cursor-pointer block bg-indigo-500 text-white border border-gray-300 rounded
                 py-2 px-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-600 dark:border-gray-500
                  2xl:dark:placeholder-gray-300 dark:text-gray-200 dark:text-gray-300')
                ->emit('bulkActionEvent', [])
        ];
    }

    public function datasource(): ?Builder
    {
        return Subject::join('pivot_gds', 'subjects.gds_id', '=', 'pivot_gds.id')
            ->join('groups', 'groups.id', '=', 'pivot_gds.group_id')
            ->join('departments', 'departments.id', '=', 'pivot_gds.department_id')
            ->join('specializes', 'specializes.id', '=', 'pivot_gds.specialize_id')
            ->select([
                'subjects.id', 'subjects.code_subject', 'subjects.max_written','subjects.max_kpis','subjects.max_applied','subjects.name as name', 'subjects.term', 'subjects.type_subject',
                'departments.name as department', 'specializes.name as specialize', 'groups.name as group'
            ])->orderBy('groups.id')->orderBy('departments.id')->orderBy('specializes.id')->orderBy('subjects.term');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('name')
            ->addColumn('name_lower', function (Subject $model) {
                return strtolower(e($model->name));
            })
            ->addColumn('group_id')
            ->addColumn('departments_id')
            ->addColumn('specialize_id')
            ->addColumn('term')
            ->addColumn('code_subject')
            ->addColumn('max_written')
            ->addColumn('max_kpis')
            ->addColumn('max_applied')

            ->addColumn('type_subject');
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array

    {
        return [
            Column::make('كود المادة', 'id')
                ->searchable(),

            Column::make('كود المادة', 'code_subject')
                ->searchable(),
//                ->makeInputSelect(Subject::select('code_subject')->distinct()->get(), 'code_subject', 'code_subject')->sortable()

            Column::make('المقرر الدراسى', 'name')
                ->searchable()
                ->makeInputSelect(Subject::select('id','name')->distinct()->get(), 'name', 'subjects.id')->sortable(),


            Column::make('الفرقة الدراسية', 'group')
                ->searchable()
                ->makeInputSelect(Subject::join('pivot_gds', 'subjects.gds_id', '=', 'pivot_gds.id')
                    ->join('groups', 'groups.id', '=', 'pivot_gds.group_id')
                    ->select('groups.name as group', 'groups.id')->distinct()->orderBy('groups.id')->get(),
                    'group', 'groups.id')->sortable(),

            Column::make(__('القسم/ الشعبة'), 'department', 'department')
                ->searchable()
                ->makeInputSelect(Subject::join('pivot_gds', 'subjects.gds_id', '=', 'pivot_gds.id')
                    ->join('departments', 'departments.id', '=', 'pivot_gds.department_id')
                    ->select('departments.name as department', 'departments.id')->distinct()->get(),
                    'department', 'departments.id')
                ->sortable(),
            Column::make(__('التخصص'), 'specialize', 'specialize')
                ->searchable()
                ->makeInputSelect(Subject::join('pivot_gds', 'subjects.gds_id', '=', 'pivot_gds.id')
                    ->join('specializes', 'specializes.id', '=', 'pivot_gds.specialize_id')
                    ->select('specializes.name as specialize', 'specializes.id')->distinct()->get(),
                    'specialize', 'specializes.id')
                ->sortable(),

            Column::make('الفصل الدراسى', 'term')
                ->searchable()
                ->makeInputSelect(Subject::select('term')->distinct()->get(), 'term', 'term')
                ->sortable(),
            Column::make('نوع المادة', 'type_subject')
                ->searchable()
                ->makeInputSelect(Subject::select('type_subject')->distinct()->get(),
                    'type_subject', 'type_subject')->sortable(),

            Column::make('التحريري ', 'max_written')
                ->searchable()->editOnClick(),
            Column::make('اعمال السنه ', 'max_kpis')
                ->searchable()->editOnClick(),
            Column::make('التطبيقي ', 'max_applied')
                ->searchable()->editOnClick(),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid Subject Action Buttons.
     *
     * @return array<int, Button>
     */


    public function actions(): array
    {
        return [
            //    Button::make('edit', 'Edit')
            //        ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2.5 m-1 rounded text-sm')
            //        ->route('subject.edit', ['subject' => 'id']),

            // Button::make('destroy', 'حذف')
            //     ->class('bg-red-500 cursor-pointer text-white px-4 py-3 text-lg m-1 rounded text-sm')
            //     ->route('subjects.destroy', ['id' => 'id'])
            //     ->method('delete')->target('_self'),
        ];
    }

    public bool $showErrorBag = true;
    protected array $rules = [
        'max_written.*' => ['required', 'integer'],
        'max_kpis.*' => ['required', 'integer'],
        'max_applied.*' => ['required', 'integer'],
    ];
    protected array $messages = [
        'required' =>  'هذا الحقل مطلوب'
    ];

    public function onUpdatedEditable(string $id, string $field, string $value): void
    {
        $this->validate();
        Subject::query()->find($id)->update([
            $field => $value,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid Subject Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($subject) => $subject->id === 1)
                ->hide(),
        ];
    }
    */
}
