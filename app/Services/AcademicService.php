<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\Grade;

class AcademicService
{
    /**
     * Cria uma nova avaliação robusta.
     */
    public function createEvaluation(array $data): Evaluation
    {
        return Evaluation::create($data);
    }

    /**
     * Registra ou atualiza notas de múltiplos alunos para uma avaliação.
     */
    public function saveGrades(int $evaluationId, array $scores)
    {
        foreach ($scores as $studentId => $score) {
            \App\Models\Grade::updateOrCreate(
                [
                    'evaluation_id' => $evaluationId,
                    'student_id'    => $studentId,
                ],
                [
                    'score' => $score
                ]
            );
        }
    }
}
