<?php

namespace App\Queries;


use Illuminate\Database\Eloquent\Builder;

class StudentQuery extends Builder
{
    public function byGroup(string $groupId): self
    {
        return $this->where('group_id', $groupId);
    }

    public function byDepartment(string $departmentId): self
    {
        return $this->where('department_id', $departmentId);
    }

    public function bySpecialize(string $specializeId): self
    {
        return $this->where('specialize_id', $specializeId);
    }

    public function byStatus(string $statusId): self
    {
        return $this->where('studystatuses_id', $statusId);
    }

    public function byYear(?string $year = null): self
    {
        if (!$year){
            return $this;
        }
        return $this->where('year', $year);
    }

    public function withTotalFailedSubjects(): self
    {
        return $this->withCount([
            'result as total_failed_subjects' => fn(Builder $query) => $query
                ->where('grade', 'ضعيف')
                ->orWhere('grade', 'ضعيف جدا')
                ->orWhere('grade', 'غياب'),
        ]);
    }

    public function withMaxSubjectResult(string $groupId, string $departmentId, string $specializeId): self
    {
        return $this->addSelect([
            'max_result' => fn($query) => $query
                ->selectRaw('COALESCE(SUM(subjects.max_written + subjects.max_applied + subjects.max_kpis), 0) as max_result')
                ->leftJoin('pivot_gds', 'pivot_gds.id', '=', 'subjects.gds_id')
                ->where('pivot_gds.group_id', '=', $groupId)
                ->where('pivot_gds.department_id', '=', $departmentId)
                ->where('pivot_gds.specialize_id', '=', $specializeId),
        ]);
    }

    public function withTotalSubjectResult(): self
    {
        return $this->addSelect([
            'total_result' => fn($query) => $query
                ->selectRaw('SUM(written + applied + kpis) as total_result')
                ->from('results')
                ->whereColumn('students.id', 'results.students_id'),
        ]);
    }

//    public function withRelations(): self
//    {
//        return $this->with([
//            'group' => fn($query) => $query->select(['id', 'name']),
//            'department' => fn($query) => $query->select(['id', 'name']),
//            'specialize' => fn($query) => $query->select(['id', 'name']),
//            'Studystatus' => fn($query) => $query->select(['id', 'name']),
//            'yearSemesterStudent.yearSemester',
//            'result',
//            'result.subject' => fn($query) => $query->orderBy('name'),
//        ]);
//    }

//    public function withSubjectPercentage(): self
//    {
//        return $this->addSelect([
//            'total_percentage' => fn($query) => $query
//                ->selectRaw('(SUM(results.written + results.applied + results.kpis) / SUM(subjects.max_written + subjects.max_kpis + subjects.max_applied) ) * 100 as total_percentage')
//                ->from('results')
//                ->join('subjects', 'subjects.id', '=', 'results.subjects_id')
//                ->whereColumn('students.id', 'results.students_id')
//                ->groupBy('students.id')
//        ]);
//    }
}
