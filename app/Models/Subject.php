<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'workload'];

    /**
     * Uma disciplina possui muitas avaliações ao longo do ano.
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Relacionamento: Uma disciplina possui muitos alunos
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'subject_student')
            ->withTimestamps();
    }
}
