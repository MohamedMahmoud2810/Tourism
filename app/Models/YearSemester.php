<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class YearSemester extends Model
{
    use HasFactory;

    protected $table = 'yearsemester';
    protected $fillable = ['id', 'year', 'semester'];
    public $timestamps = false;

    public function result()
    {
        return $this->hasMany(Result::class, 'yearsemester_id', 'id');
    }

    public function trace()
    {
        return $this->hasMany(Trace::class, 'yearsemester_id', 'id');
    }

    public function yearSemesterStudent()
    {
        return $this->hasMany(YearSemesterStudent::class, 'yearsemester_id', 'id');
    }
}
