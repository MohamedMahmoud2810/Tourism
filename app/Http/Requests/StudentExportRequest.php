<?php

namespace App\Http\Requests;

use App\Enums\ReportTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class StudentExportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'group_id' => ['required', Rule::exists('groups', 'id')],
            'department_id' => ['required', Rule::exists('departments', 'id')],
            'specialize_id' => ['required', Rule::exists('specializes', 'id')],
            'status_id' => ['required', Rule::exists('studystatuses', 'id')],
            'year' => [$this->requiredIfNotStatistics(), Rule::exists('yearsemester', 'year')],
            'report_type' => ['required', ReportTypeEnum::valid()],
        ];
    }

    private function requiredIfNotStatistics(): RequiredIf
    {
        return Rule::requiredIf(fn() => $this->input('report_type') !== ReportTypeEnum::STUDENTS_STATISTICS->value);
    }

}
