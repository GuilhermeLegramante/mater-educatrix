<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescriptiveQuestion extends Model
{
    use HasFactory;

    // Campos permitidos para atribuição em massa
    protected $fillable = [
        'question_text',    // Texto da questão
        'subject_id',   // Matéria/Disciplina (opcional)
        'classroom_id', // Turma associada (opcional)
        'order_index',  // Ordem de exibição na planilha
    ];

    /**
     * Relacionamento com a Disciplina/Matéria.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relacionamento com a Turma (Classroom).
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}