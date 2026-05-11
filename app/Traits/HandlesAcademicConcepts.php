<?php

namespace App\Traits;

trait HandlesAcademicConcepts
{
    /**
     * Converte scores brutos para a escala 0-10 baseada no percentual.
     */
    public function calculateAverage(float $score, float $maxScore): float
    {
        if ($maxScore <= 0) return 0;
        return ($score / $maxScore) * 10;
    }

    /**
     * Mapeia a média final para o conceito clássico.
     */
    public function determineConcept(float $finalAverage): string
    {
        return match (true) {
            $finalAverage >= 9.0  => 'A',
            $finalAverage >= 7.5  => 'B',
            $finalAverage >= 6.0  => 'C',
            $finalAverage >= 4.5  => 'D',
            $finalAverage >= 3.0  => 'E',
            default               => 'F',
        };
    }

    /**
     * Retorna a cor tailwind para cada conceito (Opcional)
     */
    public function getConceptColor(string $concept): string
    {
        return match ($concept) {
            'A', 'B' => 'text-gold-600 dark:text-gold-400',
            'C'      => 'text-navy-900 dark:text-slate-300',
            default  => 'text-slate-400',
        };
    }
}
