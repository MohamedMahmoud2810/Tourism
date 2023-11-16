<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studystatus extends Model
{
    use HasFactory;

    protected $table = "studystatuses";
    protected $fillable = ['id', 'name', 'created_at', 'updated_at'];
    protected $hidden = ['created_at', 'updated_at'];
    public $timestamps = false;

    public function student()
    {
        return $this->hasMany('App/Models/Student', 'studyStatus_id', 'id');
    }
}
