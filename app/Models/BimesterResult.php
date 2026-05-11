<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimesterResult extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'classroom_id', 'subject_id', 'bimester', 'concept', 'average_score', 'teacher_note'];

    public static function calculateConcept($score)
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    }
}
