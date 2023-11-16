<?php

namespace App\Http\Livewire;

use App\Models\Student;
use App\Models\Group;
use App\Models\Studystatus;
use App\Models\Department;
use App\Models\Subject;
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
    PowerGridEloquent
};

final class students extends PowerGridComponent
{
    public $name, $code, $site_no;
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
     * @return Builder<\App\Models\Student>
     */
    public function datasource(): ?Builder
    {
        return Student::query()->join('groups', function ($query) {
            $query->on('students.group_id', '=', 'groups.id');
        })->join('studystatuses', function ($query) {
            $query->on('students.studystatuses_id', '=', 'studystatuses.id');
        })->join('departments', function ($query) {
            $query->on('students.department_id', '=', 'departments.id');
        })->join('specializes', function ($query) {
            $query->on('students.specialize_id', '=', 'specializes.id');
        })->select([
            'code', 'site_no','year','type_std','training','military', 'students.id', 'students.name', 'studystatuses.name as status',
            'departments.name as department', 'bonus','groups.name as group', 'specializes.name as specialize'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    | ❗ IMPORTANT: When using closures, you must escape any value coming from
    |    the database using the `e()` Laravel Helper function.
    |
    */
    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('name')
            /** Example of custom column using a closure **/
            ->addColumn('name_lower', function (Student $model) {
                return strtolower(e($model->name));
            })
            ->addColumn('code')
            ->addColumn('site_no')
            ->addColumn('bonus')
            ->addColumn('department_id')
            ->addColumn('specialize_id')
            ->addColumn('group_id')
             ->addColumn('year')
            ->addColumn('status')
            ->addColumn('type_std')
            ->addColumn('training')
            ->addColumn('military')

            // ->addColumn('created_at_formatted', fn (Student $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'))
            // ->addColumn('updated_at_formatted', fn (Student $model) => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'))
            ;
    }



    public function columns(): array
    {
        return [
            Column::make('ID', 'id'),
            Column::make('اسم الطالب', 'name')
                ->sortable()
                ->searchable(),

            Column::make('كود الطالب', 'code')
                ->sortable()
                ->searchable()
                ->editOnClick(),

            Column::make('رقم جلوس الطالب', 'site_no')
                ->sortable()
                ->searchable()
                ->makeInputRange()
                ->editOnClick(),
            Column::make('رصيد التيسير', 'bonus'),

            Column::make('حالة الطالب', 'status')
                ->sortable()
                ->makeInputSelect(Studystatus::select('id', 'name')->distinct()->get(), 'name', 'students.studystatuses_id')->sortable()
                ->searchable(),
            Column::make('نوع حالة الطالب', 'type_std')
                ->sortable()
                ->makeInputSelect(Student::select('type_std')->distinct()->get(), 'type_std', 'students.id')->sortable()
                ->searchable(),

            Column::make('القسم/ الشعبة', 'department')
                ->makeInputSelect(Student::with('department')->get()->transform(function ($value) {
                    return ['id' => $value->department->id, 'name' => $value->department->name];
                })->unique('id'), 'name', 'department_id')
                ->sortable()->searchable(),
            Column::make('التخصص الفرعي', 'specialize')
                ->searchable()
                ->makeInputSelect(Student::with('specialize')->get()->transform(function ($value) {
                    return ['id' => $value->specialize->id, 'name' => $value->specialize->name];
                })->unique('id', 'name'), 'name', 'specialize_id')
                ->sortable(),
            Column::make('الفرقة', 'group')
                ->searchable()
                ->makeInputSelect(Group::whereNot('name','خريجين')->get(), 'name', 'students.group_id')->sortable(),
                Column::make('السنة', 'year')
                    ->sortable()
                    ->searchable(),
            Column::make('التربية العسكرية', 'military')
                ->sortable()
                ->makeInputSelect(Student::select('military')->distinct()->get(), 'military', 'students.id')->sortable()
                ->searchable(),

            Column::make('التدريب', 'training')
                ->sortable()
                ->makeInputSelect(Student::select('training')->distinct()->get(), 'training', 'students.id')->sortable()
                ->searchable(),
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
     * PowerGrid Student Action Buttons.
     *
     * @return array<int, Button>
     */


    public function actions(): array
    {
        return [
//            Button::make('show', 'عرض نتيجة الطالب')
//                ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2.5 m-2 rounded text-lg')
//                ->route('show.Results', ['id' => 'id'])->target('_self'),

//           Button::make('destroy', 'Delete')
//               ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
//               ->route('student.destroy', ['student' => 'id'])
//               ->method('delete')


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
     * PowerGrid Student Action Rules.
     *
     * @return array<int, RuleActions>
     */


    // public function actionRules(): array
    // {
    //    return [

    //        //Hide button edit for ID 1
    //         Rule::button('edit')
    //             ->when(fn($student) => $student->id === 1)
    //             ->hide(),
    //     ];
    // }

    public bool $showErrorBag = true;
    protected array $rules = [
        //'name.*' => ['required', 'min:6'],
        'code.*' => 'required|string|unique:students,code',
        'site_no.*' => ['required', 'string', 'unique:students,site_no'],
    ];

    public function onUpdatedEditable(string $id, string $field, string $value): void
    {
        // dd($id, $field, $value);
        $this->validate();

        Student::query()->find($id)->update([
            $field => $value,
        ]);
    }
}
