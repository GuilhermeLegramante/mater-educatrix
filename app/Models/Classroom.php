<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['name', 'year'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'classroom_subject')
            ->withPivot('workload') // Permite acessar $subject->pivot->workload
            ->withTimestamps();
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function preceptoryReports()
    {
        return $this->hasMany(PreceptoryReport::class);
    }
}
