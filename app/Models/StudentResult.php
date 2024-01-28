<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResult extends Model
{
    use HasFactory;
    protected  $table = 'student_results';
    protected  $fillable = ['id','yearsemester_id','student_id','grade','percentage','sum_grade'];
    public $timestamps = false;


    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
