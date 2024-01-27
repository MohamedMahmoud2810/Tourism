<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StudentStatisticsExport implements FromView
{
    private $groupId;
    private $departmentId;
    private $specializeId;
    private $year;
    private $statusId;
    private $overview;

    public function __construct($groupId, $departmentId, $specializeId, $year, $statusId, $overview)
    {
        $this->groupId = $groupId;
        $this->departmentId = $departmentId;
        $this->specializeId = $specializeId;
        $this->year = $year;
        $this->statusId = $statusId;
        $this->overview = $overview;
    }

    public function view(): View
    {
        return view('exports.students_statistics', compact(
            $this->groupId,
            $this->departmentId,
            $this->specializeId,
            $this->year,
            $this->statusId,
            $this->overview
        ));
    }
}