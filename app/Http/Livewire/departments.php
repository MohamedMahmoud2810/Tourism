<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\GroupDepartmentSpecialize;
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

final class departments extends PowerGridComponent
{
    public $name, $group_id, $group;
    public string $primaryKey = 'departments.id';
    public string $sortField = 'departments.id';

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
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    protected function getListeners()
    {
        return array_merge(
            parent::getListeners(),
            [
                'rowActionEvent',
                'bulkActionEvent',
            ]);
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
     * @return Builder<\App\Models\GroupDepartmentSpecialize>
     */
    public function datasource(): ?Builder
    {
        return Department::join('pivot_gds', 'departments.id', '=', 'pivot_gds.department_id')
            ->join('groups', 'groups.id', '=', 'pivot_gds.group_id')
            ->join('specializes', 'specializes.id', '=', 'pivot_gds.specialize_id')
            ->select([
                'pivot_gds.id as id', 'departments.name', 'specializes.name as specialize', 'groups.name as group'
            ])->orderBy('groups.id')->orderBy('departments.id')->orderBy('specializes.id');

    }


//    public function relationSearch(): array
//    {
//        return [
//            'Group' => ['name'],
//        ];
//    }

    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('name')
            ->addColumn('specialize')
            /** Example of custom column using a closure **/
            ->addColumn('name_lower', function (Department $model) {
                return strtolower(e($model->name));
            })
            // ->addColumns('group',fn($department)=>Department::group('group_id',$department->group_id)->firstWhere()['name'])
            ->addColumn('group_id');
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
            Column::make(__('الفرقة'), 'group', 'groups.name')
                ->searchable()
                ->sortable(),
            Column::make(__('القسم/ الشعبة'), 'name', 'departments.name')
                ->searchable()
                ->editOnClick()
                ->sortable(),
            Column::make(__('التخصص'), 'specialize', 'specializes.name')
                ->searchable()
                ->editOnClick()
                ->sortable(),
        ];
    }

//    public function query(): Builder
//    {
//        return Department::with('groups');
//    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid Department Action Buttons.
     *
     * @return array<int, Button>
     */

    public function actions(): array
    {
        return [
            //        Button::make('edit', 'Edit')
            //            ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2.5 m-1 rounded text-sm')
            //            ->route('department.edit', ['department' => 'id']),

            Button::make('destroy', 'حذف')
                ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-xl')
                ->route('department.destroy', ['id' => 'id'])->method('delete')
                ->target('_self'),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid Department Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($department) => $department->id === 1)
                ->hide(),
        ];
    }
    */


    protected array $rules = [
        'name.*' => ['required', 'min:6', 'unique:departments'],
    ];

    public function onUpdatedEditable(string $id, string $field, string $value): void
    {
        $this->validate();

        GroupDepartmentSpecialize::query()->find($id)->update([
            $field => $value,
        ]);
    }
}
