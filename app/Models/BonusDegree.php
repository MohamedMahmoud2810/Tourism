<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Result;

class BonusDegree extends Model
{
    use HasFactory;

    protected $table = 'bonus_degrees';
    protected $fillable = ['id', 'degree_group1', 'degree_group2', 'degree_group3', 'degree_group4', 'created_at',
        'updated_at'];
    public $timestamps = false;
    protected $guarded = [];


}
