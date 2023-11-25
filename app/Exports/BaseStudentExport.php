<?php

namespace App\Exports;

use App\Enums\ReportTypeEnum;
use App\Models\GroupDepartmentSpecialize;
use App\Models\Student;
use Doctrine\DBAL\Result;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseStudentExport
{
    protected ReportTypeEnum $reportType;
    protected string $groupId;
    protected string $departmentId;
    protected string $specializeId;
    protected string $statusId;

    protected string $year;

    protected Builder $query;
//
//    public function __construct()
//    {
//        ob_end_clean();
//        ob_start();
//    }

    public function __construct(int $groupId)
    {
        $this->groupId = $groupId;
    }

    public static function make(): static
    {
        return new static();
    }

    public function query(): Builder
    {
        $this->query = Student::with(['group', 'department', 'specialize', 'Studystatus', 'result.subject'])
            ->where('group_id', $this->groupId)
            ->where('department_id', $this->departmentId)
            ->where('specialize_id', $this->specializeId);
        if ($this->statusId !== 'all') {
            $this->query->where('studystatuses_id', $this->statusId);
        }

        return $this->query;
    }

    private function getGroupDepartmentSpecializeSubjectId(): string
    {
        return GroupDepartmentSpecialize::where('group_id', $this->groupId)
            ->where('department_id', $this->departmentId)
            ->where('specialize_id', $this->specializeId)
            ->first()
            ->id;
    }

    public function setGroupId(string $groupId): self
    {
        $this->groupId = $groupId;
        return $this;
    }

    public function setDepartmentId(string $departmentId): self
    {
        $this->departmentId = $departmentId;
        return $this;
    }

    public function setSpecializeId(string $specializeId): self
    {
        $this->specializeId = $specializeId;
        return $this;
    }

    public function setStatusId(string $statusId): self
    {
        $this->statusId = $statusId;
        return $this;
    }

    public function setYearId(string $yearId): self
    {
        $this->yearId = $yearId;
        return $this;
    }

    public function setReportType(ReportTypeEnum $reportType): BaseStudentExport
    {
        $this->reportType = $reportType;
        return $this;
    }

}
