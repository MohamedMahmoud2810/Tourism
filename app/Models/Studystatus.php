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

    public function students()
    {
        return $this->hasMany('App/Models/Student', 'studystatuses_id', 'id');
    }
}
