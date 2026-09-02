<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

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

    /**
     * Relacionamento: Uma disciplina pertence a vários usuários (professores).
     */
    public function users(): BelongsToMany
    {
        // Se a tabela pivô for 'subject_user', basta usar assim:
        return $this->belongsToMany(User::class);

        // NOTA: Se a tabela pivô tiver outro nome (ex: 'teacher_subject'),
        // passe o nome da tabela como segundo argumento:
        // return $this->belongsToMany(User::class, 'teacher_subject');
    }
}
