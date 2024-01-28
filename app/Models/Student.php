<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Result;
use App\Models\YearSemester;
use App\Queries\StudentQuery;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $fillable = ['id', 'name', 'code', 'site_no', 'studystatuses_id','type_std', 'gender', 'immigration_std',
        'department_id', 'group_id', 'specialize_id', 'year','bonus', 'classfication', 'military','training','training_third_group'];
    public $timestamps = true;
    protected $hidden = ['created_at', 'updated_at', 'gender'];


    public function scopeByGroup($query, $groupId)
    {
        return $query->where('group_id', $groupId);
    }

    public function scopeBySpecialize($query, $specializeId)
    {
        return $query->where('specialize_id', $specializeId);
    }

    public function scopeByDepartment($query, $departmentID)
    {
        return $query->where('department_id', $departmentID);
    }

    public function scopeByStatus($query, $statusId)
    {
        return $query->where('studystatuses_id', $statusId);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }
    public function scopeSelection($q)
    {
        return $q->select('id', 'code', 'site_no', 'studystatuses_id','type_std', 'name', 'department_id', 'group_id',
            'specialize_id', 'bonus', 'year' ,'classfication');
    }

    public function group()
    {
        return $this->belongsTO('App\Models\Group', 'group_id', 'id');
    }

    public function Studystatus()
    {
        return $this->belongsTO('App\Models\Studystatus', 'studystatuses_id', 'id');
    }

    public function department()
    {
        return $this->belongsTO('App\Models\Department', 'department_id', 'id');
    }

    public function specialize()
    {
        return $this->belongsTO(Specialize::class, 'specialize_id', 'id');
    }

    public function result()
    {
        return $this->hasMany(Result::class, 'students_id', 'id');
    }

    public function ResultTransferStudent()
    {
        return $this->hasMany(Result::class, 'students_id', 'id');
    }

    public function scopeSelectionTransferStudents($q)
    {
        return $q->select('id', 'code', 'department_id', 'group_id');
    }

    public function yearSemesterStudent()
    {
        return $this->hasMany(YearSemesterStudent::class, 'student_id');
    }

    public function studentResults()
    {
        return $this->hasMany(StudentResult::class);
    }


}
