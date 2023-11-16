<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Group;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';
    protected $fillable = ['id', 'name', 'created_at', 'updated_at'];
    protected $hidden = ['created_at', 'updated_at'];
    public $timestamps = true;

    public function student()
    {
        return $this->hasMany('App/Models/Student', 'department_id', 'id');
    }

    public function groupDepartmentSpecialize()
    {
        return $this->hasMany(GroupDepartmentSpecialize::class);
    }
}
