<?php

namespace App\Exports;

use App\Enums\ReportTypeEnum;
use App\Models\GroupDepartmentSpecialize;
use App\Models\Student;
use App\Models\Trace;
use App\Models\YearSemesterStudent;
use Doctrine\DBAL\Result;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseStudentExport
{
    protected ReportTypeEnum $reportType;
    protected string $groupId;
    protected string $departmentId;
    protected string $specializeId;
    protected string $statusId;

    protected $year;

    protected Builder $query;
//
//    public function __construct()
//    {
//        ob_end_clean();
//        ob_start();
//    }

    public function __construct(string $groupId, string $departmentId, string $specializeId, $year, string $statusId)
    {
        $this->groupId = $groupId;
        $this->departmentId = $departmentId;
        $this->specializeId = $specializeId;
        $this->year = $year;
        $this->statusId = $statusId;
        $this->reportType = ReportTypeEnum::STUDENTS_RESULTS;
    }

    public static function make(): static
    {
        return new static();
    }

    public function query(): Builder
    {
        $traceYear = Trace::where('yearsemester_id' , $this->year->id)->get();

        if (isset($traceYear[1]['action'])){
            $this->query = Student::with(['group', 'department', 'specialize', 'result.subject'])
            ->join('yearsemester_student', 'students.id', '=', 'yearsemester_student.student_id')
                ->where('yearsemester_student.group_id', $this->groupId)
                ->where('yearsemester_student.department_id', $this->departmentId)
                ->where('yearsemester_student.specialize_id', $this->specializeId)
            ->where('yearsemester_student.yearsemester_id', $this->year->id);
            if ($this->statusId !== 'all') {
                $yearSemesterStudent = YearSemesterStudent::where('id' , $this->year->id)->first();
                $yearSemesterStudent->where('studystatuses_id', $this->statusId);
            }
        }else{
            $this->query = Student::with(['group', 'department', 'specialize', 'result.subject'])
                ->where('students.group_id', $this->groupId)
                ->where('students.department_id', $this->departmentId)
                ->where('students.specialize_id', $this->specializeId);
            if ($this->statusId !== 'all') {
                $this->query->where('studystatuses_id', $this->statusId);
            }
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
