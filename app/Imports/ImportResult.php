<?php

namespace App\Imports;


use  App\Helpers\General;
use App\Helpers\SubjectTrait;
use App\Models\Result;
use App\Models\Student;
use App\Models\YearSemester;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImportResult implements ToModel, WithHeadingRow, WithValidation
{
    use General, SubjectTrait;

    private int $subjects_id;
    private array $year_semester;
    private array $student;
    private array $subject;

    public function __construct($subjects_id, $yearsemester_id)
    {
        $this->subjects_id = $subjects_id;
        $this->subject = $this->getSubjectInfo($subjects_id);
        $this->year_semester = YearSemester::find($yearsemester_id)->toArray();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function rules(): array
    {
        return [
            'code' => ['required',
                function ($attribute, $value, $fail) use (&$student) {
                    if (!empty($value) and Student::where('code', $value)->exists()) {
                        $this->student = Student::where('code', $value)->first()->toArray();
                        $remaining_subjects = $this->getStudentNextSubjects($this->student['id'],
                            $this->year_semester);
                        if (!in_array($this->subject['id'], array_column($remaining_subjects, 'id')))
                            $fail(' الطالب غير مسموح له بالماده');
                        $student_courses = $this->getStudentSubject($this->student['id'], $this->year_semester['id']);
                        if ($this->year_semester['semester'] != 'دور تاني') {
                            $semester_count = $this->getSemesterSubject($this->subject['gds_id'],
                                $this->year_semester['semester']);
                            if (isset($student_courses['count']['اجباري'])) {
                                if ($student_courses['count']['اجباري'] > $semester_count['mandatory'])
                                    $fail('عدد المواد الاجباري غير صحيح');
                            }
                            if (isset($student_courses['count']['اختياري'])) {
                                if ($student_courses['count']['اختياري'] >= $semester_count['elective'])
                                    $fail('عدد المواد الاختياري غير صحيح');
                            }
                        }
                        if ($this->year_semester['semester'] == 'دور تاني' and $this->student['group_id'] != 4) {
                            $fail('الدور التاني للفرقة الرابعة فقط');
                        }
                        if ($this->year_semester['semester'] == 'دور تاني' and
                            count($this->getStudentRemainingSubjects($this->student['id'],
                                $this->year_semester)) > 2) {
                            $fail('اكثر من مادتين غير متاح له دور تاني');
                        }
                    } else {
                        $fail('كود الطالب غير موجود');
                    }
                }
            ],
            'name' => ['required', 'string', 'exists:students,name',
                function ($attribute, $value, $fail) {
                    if (!empty($value) and $this->student['name'] != $value) {
                        $fail('لا يوجد اسم او الاسم خطا');
                    }
                }
            ],
            'site_no' => ['required', 'between:3,5', 'exists:students,site_no',
                function ($attribute, $value, $fail) {
                    if (!empty($value) and $this->student['site_no'] != $value) {
                        $fail('لا يوجد رقم جلوس او رقم الجلوس خطا');
                    }
                }],
            'written' => ['nullable', 'integer', 'between:0,' . $this->subject['max_written']],
            'kpis' => ['required', 'integer', 'between:0,' . $this->subject['max_kpis']],
            'applied' => ['required', 'integer', 'between:0,' . $this->subject['max_applied']],
        ];
    }

    public function model(array $row)
    {
        return new Result([
            'students_id' => $this->student['id'],
            'subjects_id' => $this->subjects_id,
            'bonus' => 0,
            'yearsemester_id' => $this->year_semester['id'],
            'written' => $row['written'],
            'applied' => $row['applied'],
            'kpis' => $row['kpis'],
            'grade' => $this->grade($this->subjects_id, $row['written'], $row['kpis'], $row['applied']),
        ]);
    }
}
