<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occurrence extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'occurrence_type_id',
        'user_id',
        'classroom_id',
        'date',
        'time',
        'description',
        'actions_taken'
    ];

    // Garante que o campo date seja tratado como objeto Carbon
    protected $casts = [
        'date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function type()
    {
        return $this->belongsTo(OccurrenceType::class, 'occurrence_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Quem registrou
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
