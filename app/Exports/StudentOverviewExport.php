<?php

namespace App\Exports;

use App\Enums\ReportTypeEnum;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentOverviewExport extends BaseStudentExport implements FromView , WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;
    private array $overview;

    public function __construct(string $groupId, string $departmentId, string $specializeId, $year, string $statusId, array $overview)
    {
        parent::__construct($groupId, $departmentId, $specializeId, $year, $statusId);
        $this->overview = $overview;
    }
    public function view(): View
    {
        $this
            ->makeYearIdNullIfTypeIsStatistics()
            ->query();

        return match ($this->reportType) {
            ReportTypeEnum::STUDENTS_STATISTICS => $this->statisticsReport(),
            default => $this->overviewReport(),
        };
    }

    private function statisticsReport(): View
    {
        return view('exports.students_statistics', [
            'overview' => $this->overview,
        ]);
    }

    private function overviewReport(): View
    {
        return view('exports.students_overview', [
            'overview' => $this->overview,
        ]);
    }

    public function getOverview(): array
    {
        $students = $this->query->get()
            ->map(fn(Student $student) => $student->setAttribute('overall_grade', $student->overall_grade));

        $totalStudentsCount = $students->count();

        $overview = [
            'enrolledStudentsCount' => $totalStudentsCount,
            'appliedStudentsCount' => $totalStudentsCount,
            'presentStudentsCount' => $totalStudentsCount,
            'absentStudentsCount' => 0,
            'suspendedStudentsCount' => 0,
            'excellentStudentsCount' => $students->where('overall_grade', 'ممتاز')->count(),
            'veryGoodStudentsCount' => $students->where('overall_grade', 'جيد جدا')->count(),
            'goodStudentsCount' => $students->where('overall_grade', 'جيد')->count(),
            'passStudentsCount' => $students->where('overall_grade', 'مقبول')->count(),
            'failedStudentsCount' => $students->where('overall_grade', 'راسب')->count(),
            'succeededStudentsCount' => $students->where('overall_grade', '!=', 'راسب')->count(),
            'oneSubjectFailedStudentCount' => $students->where('overall_grade', 'مادة')->count(),
            'twoSubjectFailedStudentCount' => $students->where('overall_grade', 'مادتين')->count(),
        ];

        $overview['totalSuccessPercentage'] = round($overview['succeededStudentsCount'] / $overview['enrolledStudentsCount'], 2) * 100;

        return $overview;
    }

    private function makeYearIdNullIfTypeIsStatistics(): self
    {
        if ($this->reportType == ReportTypeEnum::STUDENTS_STATISTICS) {
            $this->yearId = null;
        }

        return $this;
    }


    public function map($row): array
    {
        // TODO: Implement map() method.
    }

    public function styles(Worksheet $sheet)
    {
        // TODO: Implement styles() method.
    }
}
