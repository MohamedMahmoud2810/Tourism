<?php

namespace App\Enums;

enum ReportTypeEnum: int
{
    use BaseEnum;
    case STUDENTS_RESULTS = 1;
    case STUDENTS_OVERVIEW = 2;
    case STUDENTS_STATISTICS = 3;

    public function label(): string
    {
        return match ($this) {
            self::STUDENTS_RESULTS => 'نتائج الطلاب',
            self::STUDENTS_OVERVIEW => 'نظرة عامة عن الطلاب',
            self::STUDENTS_STATISTICS => 'احصائيات الطلاب',
        };
    }
}
