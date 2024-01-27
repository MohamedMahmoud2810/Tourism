<?php

namespace App\Helpers;

use App\Models\Result;
use App\Models\Trace;

trait General
{
    public function removeArabicChar(?string $string): ?string
    {
        if (!is_null($string)) {
            $string = str_replace("ى", "ي", $string);
            $string = str_replace("ة", "ه", $string);
            $string = str_replace(["أ", "آ", "إ"], "ا", $string);
            $string = str_replace("پ", "ب", $string);
            $string = str_replace("ژ", "ز", $string);
            $string = str_replace("ڤ", "ف", $string);
            $string = str_replace("گ", "ك", $string);
            $string = str_replace("ﷲ", "الله", $string);
        }
        return $string;
    }


    public function allResultsArray(array $data): array
    {
        $id = 1;
        return array_values(Result::join('subjects', 'results.subjects_id', '=', 'subjects.id')
            ->join('students', 'results.students_id', '=', 'students.id')
            ->join('yearsemester_student', function ($query) use ($data) {
                $query
                    ->on('yearsemester_student.student_id', '=', 'students.id')
                    ->where('yearsemester_student.group_id', $data['group_id'])
                    ->where('yearsemester_student.department_id', $data['department_id'])
                    ->where('yearsemester_student.specialize_id', $data['specialize_id']);
                if ($data['studystatuses_id'] != 'all') {
                    $query->where('yearsemester_student.studystatus_id', $data['studystatuses_id']);
                }
            })->join('studystatuses', 'students.studystatuses_id', '=', 'studystatuses.id')->distinct()
            ->whereIn('results.yearsemester_id', $data['yearsemester_id'])->select([
                'results.id', 'students.name as nameStd', 'students.code as code',
                'students.site_no as site_no', 'studystatuses.name as status','students.bonus as remaining_bonus',
                'written', 'results.bonus', 'kpis', 'applied', 'grade', 'subjects.name as subject', 'subjects_id',
                'students_id'
            ])->selectRaw('written + applied + kpis + results.bonus as total')->get()->groupBy('students_id')
            ->transform(function ($value) use (&$id) {
                $student = [
                    'id' => $id++,
                    'nameStd' => $value[0]->nameStd,
                    'code' => $value[0]->code,
                    'site_no' => $value[0]->site_no,
                    'status' => $value[0]->status,
                    'remaining_bonus' => $value[0]->remaining_bonus,
                    'total_bonus' => 0
                ];
//                dd($value);
                for ($i = 0; $i < count($value); $i++) {
                    $student['total_bonus'] += $value[$i]->bonus;
                    $student[$value[$i]->subject] = [
                        'id' => $value[$i]->id,
                        'written' => $value[$i]->written,
                        'applied' => $value[$i]->applied,
                        'kpis' => $value[$i]->kpis,
                        'bonus' => $value[$i]->bonus,
                        'total' => $value[$i]->total,
                        'grade' => $value[$i]->grade,
                    ];
                }
                return $student;
            })->toArray());
    }

    public function getNextAction(): array
    {
        $trace = Trace::with('yearSemester')->latest()->first();
        if ($trace->action == 'second_attempt_up_level') {
            $action = 'add_bonus';
            $year = explode('/', $trace->yearSemester->year)[1];
            $year .= '/' . ($year + 1);
            $semester = 'ثاني';
        } elseif ($trace->action == 'add_bonus') {
            $action = 'up_level';
            $year = $trace->yearSemester->year;
            $semester = 'ثاني';
        } elseif ($trace->action == 'up_level') {
            $action = 'second_attempt_add_bonus';
            $year = $trace->yearSemester->year;
            $semester = 'دور تاني';
        }else{  // Here Action is => second_attempt_add_bonus
            $action = 'second_attempt_up_level';
            $year = $trace->yearSemester->year;
            $semester = 'دور تاني';
        }
        //dd($trace->action,$semester,$year);
        return compact('year', 'semester', 'action');
    }
}
