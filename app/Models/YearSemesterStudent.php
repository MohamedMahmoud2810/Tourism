<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearSemesterStudent extends Model
{
    use HasFactory;

    protected $table = 'yearsemester_student';
    protected $fillable = ['id', 'student_id', 'yearsemester_id', 'group_id',
        'department_id', 'specialize_id', 'studystatus_id', 'site_no'];
    public $timestamps = false;

    public function student()
    {
        return $this->belongsTo(Student::class,'student_id','id');
    }
    public function yearSemester()
    {
        return $this->belongsTo(YearSemester::class,'yearsemester_id','id');
    }

}
