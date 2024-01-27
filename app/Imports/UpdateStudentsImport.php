<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UpdateStudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function rules(): array
    {
        return [
            'code' => 'required|exists:students,code',
            'training' => ['required_with_all:*.military','string','in:ناجح,راسب'],
            'military' => ['required_with_all:*.training','string','in:معفي,اجتاز,لم يجتاز'],
            'training_third_group' =>[ 'required_without_all:*.training,*.military','string','in:ناجح,راسب'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'required' => 'هذا الحقل مطلوب',
            'exists' => 'هذا الحقل غير موجود',
            'string.training' => 'يجب ان يكون التدريب حروف ',
            'string.military' => 'يجب ان يكون التربية العسكرية حروف ',
            'in.training' => 'يجب ان يكون التدريب ناجح او راسب ',
            'in.military' => 'يجب ان يكون التربية العسكرية معفي او اجتاز او لم يجتاز ',
        ];
    }


    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
       // dd($row);
        Student::where('code', $row['code'])->update([
            'training' => $row['training'],
            'military' => $row['military'],
            'training_third_group' => $row['training_third_group'],
        ]);
        return null;
    }
}
