<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultTransferStudent extends Model
{
    use HasFactory;
    protected $table = 'result_transfer_students';
    protected $fillable = ['id','year','students_id','grade','subjects_id','degree',
        'created_at','updated_at'];
    protected $hidden = ['created_at','updated_at'];
    public $timestamps = false;

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subjects_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'students_id', 'id');
    }

    public function yearSemester()
    {
        return $this->belongsTo(YearSemester::class, 'yearsemester_id', 'id');
    }
}
