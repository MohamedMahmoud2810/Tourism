<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialize extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'created_at', 'updated_at'];
    protected $hidden = ['created_at', 'updated_at'];
    public $timestamps = false;

    public function student()
    {
        return $this->hasMany('App/Models/Student', 'specialize_id', 'id');
    }

    public function groupDepartmentSpecialize()
    {
        return $this->hasMany(GroupDepartmentSpecialize::class);
    }
}
