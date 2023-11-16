<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Result;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';
    protected $fillable = ['id', 'name', 'code_subject', 'gds_id','max_written','max_kpis','max_applied',
        'term', 'type_subject', 'created_at', 'updated_at'];
    protected $hidden = ['created_at', 'updated_at'];
    public $timestamps = false;


    public function groupDepartmentSpecialize()
    {
        return $this->belongsTo(GroupDepartmentSpecialize::class, 'gds_id', 'id');
    }

    public function result()
    {
        return $this->hasMany(Result::class, 'subjects_id', 'id');

    }
}
