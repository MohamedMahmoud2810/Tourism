<?php

namespace App\Imports;

use App\Helpers\General;
use App\Models\Student;
use App\Models\BonusDegree;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithValidation;


class ImportStudent implements ToModel, WithHeadingRow, WithValidation
{
    use General;

    private int $department_id;
    private int $specialize_id;
    private string $year;
    private string $type_std;
    private int $group_id;
    private int $studystatuses_id;

    public function __construct($department_id, $specialize_id, $group_id, $studystatuses_id,$type_std,$year)
    {
        $this->department_id = $department_id;
        $this->specialize_id = $specialize_id;
        $this->year = $year;
        $this->type_std = $type_std;
        $this->group_id = $group_id;
        $this->studystatuses_id = $studystatuses_id;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
   

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'code' => ['required', 'unique:students'],
            'site_no' => ['required', function($attribute, $value, $fail){
                  if(Student::where('site_no',$value)->where('year', $this->year)->exists()){
                     $fail('رقم الجلوس في هذه السنه موجود من قبل');
                    }
            }],
            'immigration_std' => ['required', 'string', 'in:مصري,وافد'],
            'gender' => ['required', 'string', 'in:انثي,ذكر'],
        ];
    }

    public function model(array $row)
    {
        $bonuGgroup = BonusDegree::first()->getOriginal()['degree_group' . $this->group_id];
        return new Student([
            'name' => $this->removeArabicChar($row['name']),
            'code' => $row['code'],
            'site_no' => $row['site_no'],
            'immigration_std' => $row['immigration_std'],
            'gender' => $row['gender'],
            'military' => ($row['gender'] == 'انثي') ? 'انثي' : ($row['immigration_std'] == 'وافد' ? 'وافد' : 'لم يجتاز'),
            'department_id' => $this->department_id,
            'specialize_id' => $this->specialize_id,
            'year' => $this->year,
            'type_std' => $this->type_std,
            'group_id' => $this->group_id,
            'studystatuses_id' => $this->studystatuses_id,
            'bonus' => $bonuGgroup,
        ]);
    }

}
