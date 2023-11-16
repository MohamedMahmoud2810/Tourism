<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;
use App\Models\Student;
use App\Models\BonusDegree;
use App\Models\YearSemester;

class Result extends Model
{
    use HasFactory;

    protected $table = "results";
    protected $fillable = ['id', 'students_id', 'bonus', 'yearsemester_id', 'subjects_id', 'applied', 'written', 'kpis',
        'grade', 'created_at', 'updated_at'];
    protected $primaryKey = 'id';
    public $timestamps = true;


    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subjects_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class,'students_id','id');
    }

    public function yearSemester()
    {
        return $this->belongsTo(YearSemester::class, 'yearsemester_id', 'id');
    }
}
