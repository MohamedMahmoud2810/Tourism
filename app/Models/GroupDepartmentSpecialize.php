<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupDepartmentSpecialize extends Model
{
    use HasFactory;

    protected $table = 'pivot_gds';
    protected $fillable = ['id', 'group_id', 'department_id', 'specialize_id'];
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function subject()
    {
        return $this->hasMany(Subject::class, 'gds_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function specialize()
    {
        return $this->belongsTo(Specialize::class);
    }

    public function semesterSubjectCount()
    {
        return $this->belongsTo(SemesterSubjectCount::class);
    }
}
