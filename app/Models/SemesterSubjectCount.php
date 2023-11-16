<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterSubjectCount extends Model
{
    use HasFactory;

    protected $table = 'semester_subject_count';
    protected $fillable = ['id', 'gds_id', 'elective', 'mandatory'];
    public $timestamps = false;

    public function groupDepartmentSpecialize()
    {
        return $this->hasMany(GroupDepartmentSpecialize::class);
    }

}
