<?php

namespace App\Exports;

use App\Models\GroupDepartmentSpecialize;
use App\Models\Student;
use App\Models\YearSemester;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;


class StudentResultsExport extends BaseStudentExport implements FromView
{
    use Exportable;

    protected $results;
    protected string $groupId;
    protected string $departmentId;

    protected string $specializeId;

    protected $year;

    protected string $statusId;

    public function __construct($results ,string $groupId , string $departmentId , string $specializeId ,  $year , string $statusId)
    {
        $this->results = $results;
        $this->groupId = $groupId;
        $this->departmentId = $departmentId;
        $this->specializeId = $specializeId;
        $this->year = $year;
        $this->statusId = $statusId;
    }

    public function collection()
    {
        return collect($this->results);
    }

    public function view(): View
    {

        $this->query();
        $students = $this->query->get();
        $firstRecord = $this->query->first();
        return view('exports.export_students', [
            'group' => $firstRecord->group,
            'department' => $firstRecord->department,
            'specialize' => $firstRecord->specialize,
            'status' => $firstRecord->Studystatus,
            'year' => $this->year,
            'students' => $students,
            'subjectNames' => $firstRecord->result->pluck('subject.name'),
            'subjectDistributions' => $this->getSubjectDistributions($firstRecord),
        ]);
    }

    public function view_pdf(): View
    {

        $this->query();
        $students = $this->query->get();
        $firstRecord = $this->query->first();
        return view('exports.export_students_pdf', [
            'group' => $firstRecord->group,
            'department' => $firstRecord->department,
            'specialize' => $firstRecord->specialize,
            'status' => $firstRecord->Studystatus,
            'year' => $this->year,
            'students' => $students,
            'subjectNames' => $firstRecord->result->pluck('subject.name'),
            'subjectDistributions' => $this->getSubjectDistributions($firstRecord),
        ]);
    }

    private function getSubjectDistributions($firstRecord): Collection
    {
        return $firstRecord->result->pluck('subject')
            ->map(fn($subject) => [
                'max_written' => $subject->max_written,
                'max_kpis' => $subject->max_kpis,
                'max_applied' => $subject->max_applied,
                'max_grade' => $subject->max_written + $subject->max_kpis + $subject->max_applied,
                'min_grade' => ($subject->max_written + $subject->max_kpis + $subject->max_applied) / 2,
            ]);
    }
}