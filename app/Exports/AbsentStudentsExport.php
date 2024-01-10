<?php

namespace App\Exports;

use App\Models\Student;
use App\Models\YearSemester;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;

class AbsentStudentsExport implements FromCollection, WithHeadings
{
    protected $subjectName;

    public function __construct($subjectName)
    {
        $this->subjectName = $subjectName;
    }


    public function collection()
    {
        $subjectsId = request('subjects_id');
        $groupId = request('group_id');
        $departmentId = request('department_id');
        $specializeId = request('specialize_id');
        $yearSemester = YearSemester::where('year' , request('year'))->where('semester' , request('semester'))->first();
        $absentStudent = Student::join('yearsemester_student', 'students.id', '=', 'yearsemester_student.student_id')
            ->join('results', 'students.id', '=', 'results.students_id')
            ->where('yearsemester_student.group_id', $groupId)
            ->where('yearsemester_student.department_id', $departmentId)
            ->where('yearsemester_student.specialize_id', $specializeId)
            ->where('yearsemester_student.yearsemester_id', $yearSemester->id)
            ->where('results.subjects_id', $subjectsId)
            ->where('results.grade', ['ضعيف جدا' , 'ضعيف'])
            ->get(['students.name', 'students.code']);

        return $absentStudent;
    }

    public function headings(): array
    {
        return [
            [$this->subjectName , ''],
            ['Student Name', 'Code'],
        ];
    }


}
