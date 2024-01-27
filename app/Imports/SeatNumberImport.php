<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SeatNumberImport implements ToModel, WithHeadingRow, WithValidation
{

    public function rules(): array
    {
        return [
            'code' => 'required|exists:students,code',
            'site_no' => ['required', 'unique:students'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'required' => 'هذا الحقل مطلوب',
            'exists' => 'هذا الحقل غير موجود',
            'unique' => 'هذه القيمه موجوده من قبل',
        ];
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
            Student::where('code', $row['code'])->update([
                'site_no' => $row['site_no'],
            ]);
            return null;

    }
}
