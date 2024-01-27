<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trace extends Model
{
    use HasFactory;

    protected $table = 'trace';
    protected $fillable = ['id', 'yearsemester_id', 'action'];

    public function yearSemester()
    {
        return $this->belongsTo(YearSemester::class, 'yearsemester_id','id');
    }
}
