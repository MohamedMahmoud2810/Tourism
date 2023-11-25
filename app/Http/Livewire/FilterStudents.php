<?php

namespace App\Http\Livewire;

use App\Enums\ReportTypeEnum;
use App\Models\Department;
use App\Models\Group;
use App\Models\GroupDepartmentSpecialize;
use App\Models\Specialize;
use App\Models\Studystatus;
use App\Models\YearSemester;
use App\Queries\StudentQuery;
use Livewire\Component;

class FilterStudents extends Component
{

    public ?string $selectedDepartmentId = null;
    public ?string $selectedGroupId = null;

    public function render()
    {
        if ($this->selectedDepartmentId && $this->selectedGroupId) {
            $specializesIds = GroupDepartmentSpecialize::query()
                ->where('group_id', $this->selectedGroupId)
                ->where('department_id', $this->selectedDepartmentId)
                ->pluck('specialize_id')
                ->toArray();

            $specializes = Specialize::query()
                ->whereIn('id', $specializesIds)
                ->get();
        }

        return view('livewire.filter-students', [
            'groups' => Group::all(),
            'departments' => Department::all(),
            'specializes' => $specializes ?? Specialize::all(),
            'status' => Studystatus::all(),
            'years' => YearSemester::all(),
            'reports' => ReportTypeEnum::cases()
        ]);
    }


}
