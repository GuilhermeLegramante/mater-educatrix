<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'subject_id',
        'title',
        'description',
        'date',
        'weight',
        'bimester',
        'applied_at',
        'max_score',
    ];

    protected $casts = [
        'applied_at' => 'date',
    ];

    /**
     * A avaliação pertence a uma disciplina específica.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Uma avaliação possui notas de vários alunos.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Uma avaliação pertence a uma Turma
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
