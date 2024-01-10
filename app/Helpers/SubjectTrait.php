<?php

namespace App\Helpers;


use App\Http\Livewire\students;
use App\Models\GroupDepartmentSpecialize;
use App\Models\Result;
use App\Models\SemesterSubjectCount;
use App\Models\Student;
use App\Models\Subject;
use http\Exception;
use function PHPUnit\Framework\lessThanOrEqual;

trait SubjectTrait
{
    public function getSpecializtionSubject(int $group_id, int $department_id, int $specialize_id, string $term = null): array
    {
        if ($term) {
            return GroupDepartmentSpecialize::where(compact('group_id', 'department_id',
                'specialize_id'))->with('subject')->first()->subject()->get()->where('term', $term)->values()
                ->transform(function ($value) {
                    return $this->getSubjectInfo($value->id);
                })
                ->toArray();
        }
        return GroupDepartmentSpecialize::where(compact('group_id', 'department_id',
            'specialize_id'))->with('subject')->first()->subject()->get()->values()->transform(function ($value) {
            return $this->getSubjectInfo($value->id);
        })->toArray();
    }

    public function getSubjectInfo($id): array
    {
        $array = Subject::with(['groupDepartmentSpecialize.group', 'groupDepartmentSpecialize.department',
            'groupDepartmentSpecialize.specialize'])->find($id)->toArray();
        $subject = array_slice($array, 0, -1);
        $subject['group'] = $array['group_department_specialize']['group'];
        $subject['department'] = $array['group_department_specialize']['department'];
        $subject['specialize'] = $array['group_department_specialize']['specialize'];
        return $subject;
    }

    public function getStudentSubject(int $students_id, int $yearsemester_id = null): array
    {
        if (is_null($yearsemester_id)) {
            $subjects = Result::where(compact('students_id'))->get()->toArray();
            $subjects['count'] = Result::where(compact('students_id'))->with('subject')
                ->get()->groupBy('subject.type_subject')->map->count()->toArray();
        } else {
            $subjects = Result::where(compact('students_id', 'yearsemester_id'))->get()->toArray();
            $subjects['count'] = Result::where(compact('students_id', 'yearsemester_id'))->with('subject')
                ->get()->groupBy('subject.type_subject')->map->count()->toArray();
        }
        return $subjects;
    }

    public function getSemesterSubject(int $gds_id, string $term = null)
    {
        if ($term) {
            return SemesterSubjectCount::where(compact('gds_id', 'term'))->first()->toArray();
        } else {
            return SemesterSubjectCount::where(compact('gds_id'))->first()->toArray();
        }
    }

    public function getStudentGroupSubjects(int $student_id, string $term = null): array
    {
        $student = Student::find($student_id);
        return $this->getSpecializtionSubject($student->group_id, $student->department_id, $student->specialize_id, $term);
    }

    public function getStudentRemainingSubjects(int $student_id, $year_semester = null)
    {
        $student_subject = $this->getStudentSubject($student_id);
        unset($student_subject['count']);
        $subjects = collect($student_subject)->sortBy('yearsemester_id')->groupBy('subjects_id')
            ->transform(function ($value) use ($year_semester) {
                if (in_array($value->last()['grade'], ['ضعيف', 'ضعيف جدا', 'راسب تحريرى', 'غياب' , 'عذر'])) {
                    return $value;
                }
            })->whereNotNull()->keys()->transform(function ($value) {
                return $this->getSubjectInfo($value);
            });
        if ($year_semester['semester'] != 'دور تاني') {
            $subjects = $subjects->where('term', $year_semester['semester'])->values();
        }
        return $subjects->toArray();
    }

    public function getStudentNextSubjects(int $student_id, $year_semester = null): array
    {
        $student = Student::find($student_id)->toArray();
        $next_subject = [];
        if ($student['studystatuses_id'] == 1) {
            $next_subject = $this->getStudentGroupSubjects($student_id, $year_semester['semester']);
        }
        $fail_subject_ids = $this->getStudentRemainingSubjects($student_id, $year_semester);
        $subject = $this->getStudentSubject($student_id, $year_semester['id']);
        unset($subject['count']);
        return collect(array_merge($next_subject, $fail_subject_ids))
            ->whereNotIn('id', array_column($subject, 'subjects_id'))->values()->toArray();
    }

    public function grade(int $subject_id, ?int $written, int $kpis, int $applied, int $bonus = 0): string
    {
    $subject = $this->getSubjectInfo($subject_id);
    $total = $written + $kpis + $applied + $bonus;
    $subject_total = $subject['max_written'] + $subject['max_kpis'] + $subject['max_applied'];

    if ($written === null) {
        return 'غياب';
    }
    if ($written == -1){
        return 'عذر';
    }
    if ($subject['max_written'] > 0 and $written < $subject['max_written'] * 0.3 and $total >= 44 ) {
        return 'راسب تحريرى';
    }
    if ($total < $subject_total * 0.3) {
        return "ضعيف جدا";
    }
    if ($total < $subject_total * 0.5) {
        return "ضعيف";
    }
    if ($total < $subject_total * 0.65) {
        return "مقبول";
    }
    if ($total < $subject_total * 0.75) {
        return "جيد";
    }
    if ($total < $subject_total * 0.85) {
        return "جيد جدا";
    }
    if ($total <= $subject_total * 1) {
        return "ممتاز";
    }

    return '';
}

    public function calculateBonus($total,$id=-1): int
    {
        $bonus = 0;
        $bonus_array = [
            '64' => 1, '74' => 1,
            '84' => 1, '44' => 6,
            '46' => 4, '47' => 3,
            '48' => 2, '49' => 1,
            '45' => 5,
        ];
        if (in_array($total, array_keys($bonus_array))) {
            $bonus += $bonus_array[$total];
        }
        return $bonus;
    }

    public function generalGrade(int $degree, int $total): string
    {
       if ($degree==0)return '';
       if($degree == 'عذر'){
           return 'عذر';
       }
        $percent = $degree / $total;
        if ($percent >= 0.50 and $percent < 0.65) {
            return "مقبول";
        }
        if ($percent >= 0.65 and $percent < 0.75) {
            return "جيد";
        }
        if ($percent >= 0.75 and $percent < 0.85) {
            return "جيد جدا";
        }
        if ($percent >= 0.85 and $percent <= 1) {
            return "ممتاز";
        }
        return '';
    }

//    public function calculateTotal(int $student_id, int $gds)
//    {
//        $degree = 0;
//        $total = Result::with(['subject' => function ($query) use ($gds) {
//            $query->where('gds_id', $gds);
//        }])->where('students_id', $student_id)->get()->groupBy('subjects_id')
//            ->sum(function ($value) {
//                return $value[0]->subject->max_written + $value[0]->subject->max_kpis + $value[0]->subject->max_applied;
//            });
//
//        $success_results = Result::with('student')->where('students_id', $student_id)->
//        whereNotIn('grade', ['غياب','راسب تحريرى', 'ضعيف جدا', 'ضعيف'])->get()
//            ->groupBy('subjects_id');
//        foreach ($success_results as $result) {
//            $degree += ($result->first()->written ?? 0) + ($result->first()->kpis ?? 0)
//                + ($result->first()->applied ?? 0) + ($result->first()->bonus ?? 0);
//        }
//
//        $failed_results_count = Result::whereIn('subjects_id', function ($query) {
//            $query->select('subjects_id')
//                ->from('results')
//                ->groupBy('subjects_id')
//                ->havingRaw('MAX(yearsemester_id) = results.yearsemester_id');
//        })
//            ->where('students_id', $student_id)
//            ->where('grade', ['غياب','راسب تحريرى', 'ضعيف جدا', 'ضعيف'])
//            ->count();
//
//        if ($failed_results_count == 0) {
//            return $this->generalGrade($degree, $total);
//        } else {
//            $failed_degree = [1 => 'مادة', 2 => 'مادتين'];
//            return $failed_degree[$failed_results_count];
//        }
//
//
//    }





    public function getStudentYearsSemester($StudentId){

       $StudentYearsSemester = Result::with('student')
         ->where('students_id',$StudentId)
         ->get();

       return ['min'=>($StudentYearsSemester->min('yearsemester_id')),
           'max'=> ($StudentYearsSemester->max('yearsemester_id'))]  ;

    }
    public  function test($student_id){
       $i= $this->getStudentYearsSemester($student_id)['min'];
       $f= $this->getStudentYearsSemester($student_id)['max'];


        $level=1;
        for ($ii=$i; $ii<=$f;$ii+=2){

            //$i  first term
            //$i+1  second term


                $success_results = Result::with('student')
                    ->where('students_id', $student_id)
                    -> whereNotIn('grade', ['غياب','راسب تحريرى', 'ضعيف جدا', 'ضعيف','عذر'])
                    ->whereIn('yearsemester_id',[$ii, $ii + 1])
                    ->get()
                    ->groupBy('subjects_id');


            foreach ($success_results as $item) {
                foreach ($item as $result) {
                    $subject_id = $result->subjects_id;
                    // do something with $subject_id
                    $gds = Subject::where('id',$subject_id)->select('gds_id')->get();
                    dd($gds);
                }
            }
            print ( ' ___________________________________________________________________');
//                dd($success_results);
//                if (count($success_results) >= 14){
//                    // pass from this level
//                    print($level);
//                }
//                print (count($success_results) . ' \n ');



            //$i+2  first term 2nd year
           // print($i);

//            foreach ($success_results as $result) {
////                    $degree += ($result->first()->written ?? 0) + ($result->first()->kpis ?? 0)
////                        + ($result->first()->applied ?? 0) + ($result->first()->bonus ?? 0);
//
//            print ($result . '\n ____________________________________________________________________ \n');
//
//            }

            $level++;
        }


    }


    public function totalGrade($student_id):array
    {

        $SubjectsInLevel = [];
        $StudentGrad=[];


        for ($i = 1; $i <= 4; $i++) {
            $gds = Subject::join('pivot_gds', 'subjects.gds_id', '=', 'pivot_gds.id')
                ->join('groups', 'groups.id', '=', 'pivot_gds.group_id')
                ->where('groups.id', $i)
                ->select('subjects.id')
                ->get()
            ->toArray();

            $SubjectsInLevel += [$i => array($gds)];
        }
        $AllYearsTotal=0;
        $AllYearsTotalDegree=0;
        for ($group = 1; $group <= 4; $group++) {

            $NoOfSuccessedSubjectInLevel = 0;
            $degree = 0;
            $total = 0;
            for ($j = 0; $j < count($SubjectsInLevel[$group][0]); $j++) {
                $subId = $SubjectsInLevel[$group][0][$j]['id'];

                $Succeded_subject = (Result::with('student')
                    ->where('students_id', $student_id)
                    ->where('subjects_id', $subId)
                    ->whereNotIn('grade', ['غياب', 'راسب تحريرى', 'ضعيف جدا', 'ضعيف' , 'عذر'])->get()
                );
                if (count($Succeded_subject) == 1) {


//                    $subject = Subject::find($Succeded_subject[0]['id']);
                    $subject = Subject::find($subId);

                    $degree +=  $Succeded_subject[0]['written']+$Succeded_subject[0]['kpis'] +$Succeded_subject[0]['applied'] +
                    $Succeded_subject[0]['bonus'];
                    $total += $subject['max_written'] + $subject['max_kpis'] + $subject['max_applied'];


                    $AllYearsTotal +=$total;
                    $AllYearsTotalDegree +=$degree;

                    $NoOfSuccessedSubjectInLevel += 1;


                }


            }
            $student=Student::find($student_id);
            if ($group==4 and $NoOfSuccessedSubjectInLevel != 0 )//he is in level 4
                {
                    if ($student['training']=='راسب' ){
                        $NoOfSuccessedSubjectInLevel-=1;
                    }
                    if ($student['military']=='لم يجتاز' ){
                        $NoOfSuccessedSubjectInLevel-=1;
                    }
                }

            if (($group==3 or $group==4) and $student['training_third_group']=='راسب'){
                $NoOfSuccessedSubjectInLevel-=1;
            }
            if ($NoOfSuccessedSubjectInLevel == 14) {
                $StudentGrad +=[$group=>$this->generalGrade($degree, $total)];
                if ($group==4 ){
                    $StudentGrad += [5 => $this->generalGrade($AllYearsTotalDegree,$AllYearsTotal)];
                }

            }
            else if ($NoOfSuccessedSubjectInLevel == 13) {
                $StudentGrad +=[$group=>'مادة'];

            } else if ($NoOfSuccessedSubjectInLevel == 12) {
                $StudentGrad +=[$group=>'مادتين'];
            }
            else if ($total==0) {
                if($student['group_id'] == $group){
                     $StudentGrad +=[$group=>'غائب'];
                }
            }
            else if($total == 'عذر'){
                $StudentGrad += [$group=>'وقف قيد'];
            }
            else{
                $StudentGrad +=[$group=>'راسب'];
            }
        }


        //[1==>"1st level",
        //2==>"2nd level",
        //3==>3rd level,
        //4 ==> 4 level,
        //5 ==> اتخرج]

         return $StudentGrad;

    }

    public function test2($group){

        $Ids=
            Student::
            select('id')
                ->where('group_id',$group)
            ->get();//هتجيب array ب ال ids بتوع سنه معينة

        $arr=[];

        foreach ($Ids->toArray() as $id){
          $arr [] = [$id,$this->totalGrade($id['id'])];
        }

        dd($arr);
    }

}
