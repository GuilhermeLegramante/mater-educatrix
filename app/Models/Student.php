<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'registration_number',
        'birth_date'
    ];

    /**
     * Relacionamento: Um aluno pode estar matriculado em várias turmas 
     * (ao longo dos anos) e uma turma tem vários alunos.
     */
    public function classrooms()
    {
        // 'enrollments' é a tabela pivô que criamos na migration
        return $this->belongsToMany(Classroom::class, 'enrollments')
            ->withPivot('status') // Permite acessar se ele passou ou está ativo
            ->withTimestamps();
    }

    /**
     * Relacionamento com as Notas
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Relacionamento com os Relatórios de Preceptoria
     */
    public function preceptoryReports()
    {
        return $this->hasMany(PreceptoryReport::class);
    }

    // Calcula o conceito baseado na soma de scores da turma/disciplina
    public function getConcept(int $classroomId, int $subjectId, int $bimester)
    {
        $grades = $this->grades()->whereHas('evaluation', function ($q) use ($classroomId, $subjectId, $bimester) {
            $q->where('classroom_id', $classroomId)
                ->where('subject_id', $subjectId)
                ->where('bimester', $bimester);
        })->with('evaluation')->get();

        if ($grades->isEmpty()) return 'N/A';

        $totalScore = $grades->sum('score');
        $maxPossible = $grades->sum(fn($g) => $g->evaluation->max_score);

        $percentage = ($totalScore / $maxPossible) * 10; // Escala 0-10

        return match (true) {
            $percentage >= 9.0  => 'A',
            $percentage >= 7.5  => 'B',
            $percentage >= 6.0  => 'C',
            $percentage >= 4.5  => 'D',
            $percentage >= 3.0  => 'E',
            default             => 'F',
        };
    }

    /**
     * Avaliações que o aluno participou (através das notas)
     */
    public function evaluations()
    {
        return $this->hasManyThrough(Evaluation::class, Grade::class, 'student_id', 'id', 'id', 'evaluation_id');
    }

    /**
     * Calcula o conceito consolidado, priorizando lançamentos manuais do professor.
     */
    // public function getConsolidatedConcept($classroomId, $subjectId, $bimester)
    // {
    //     // 1. Tenta buscar o resultado oficial/manual lançado pelo professor
    //     $manualResult = \App\Models\BimesterResult::where('student_id', $this->id)
    //         ->where('classroom_id', $classroomId)
    //         ->where('subject_id', $subjectId)
    //         ->where('bimester', $bimester)
    //         ->first();

    //     // 2. Se o professor já lançou um conceito manual, ele PREVALECE
    //     if ($manualResult && $manualResult->concept) {
    //         return $manualResult->concept;
    //     }

    //     // 3. Caso contrário, faz o cálculo automático baseado nas notas das avaliações
    //     $grades = $this->grades()->whereHas('evaluation', function ($q) use ($classroomId, $subjectId, $bimester) {
    //         $q->where('classroom_id', $classroomId)
    //             ->where('subject_id', $subjectId)
    //             ->where('bimester', $bimester);
    //     })->get();

    //     if ($grades->isEmpty()) return '-';

    //     $totalScore = $grades->sum('score');
    //     $totalMax = $grades->sum(fn($g) => $g->evaluation->max_score);

    //     if ($totalMax == 0) return '-';

    //     $percentage = ($totalScore / $totalMax) * 100;

    //     return $this->calculateGradeConcept($percentage);
    // }

    /**
     * Retorna a turma atual do aluno
     */
    public function currentClassroom()
    {
        return $this->classrooms()
            ->wherePivot('status', 'active');
    }

    public function getCurrentClassroomNameAttribute()
    {
        return $this->classrooms()
            ->wherePivot('status', 'active')
            ->first()
            ?->name;
    }

    /**
     * Relacionamento com os Resultados Consolidados/Manuais lançados pelos professores
     */
    public function bimesterResults()
    {
        return $this->hasMany(BimesterResult::class);
    }

    /**
     * Relacionamento: Um aluno possui muitos registros de ocorrências
     */
    public function occurrences()
    {
        return $this->hasMany(Occurrence::class)->orderBy('date', 'desc');
    }

    /**
     * Define a relação de um estudante com os seus registros de presença/falta.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Calcula o total de faltas do aluno em uma disciplina e bimestre específicos.
     */
    public function getBimesterAbsences(int $classroomId, int $subjectId, int $bimester): int
    {
        // 1. Busca as configurações globais de datas
        $settings = SchoolSetting::first();
        if (!$settings) return 0;

        $period = $settings->getBimesterPeriod($bimester);

        // Se as datas do bimestre não estiverem cadastradas, aborta
        if (!$period['start'] || !$period['end']) return 0;

        // 2. Faz a query buscando presenças onde is_absent = true dentro do período do dia letivo
        return $this->hasMany(Attendance::class)
            ->where('subject_id', $subjectId)
            ->where('is_absent', true)
            ->whereHas('schoolDay', function ($query) use ($classroomId, $period) {
                $query->where('classroom_id', $classroomId)
                    ->whereBetween('date', [$period['start'], $period['end']]);
            })
            ->count();
    }

    /**
     * Calcula a nota numérica do aluno em escala de 0 a 10 no bimestre/disciplina,
     * somando o ajuste da Avaliação Qualitativa (-1,0 a +1,0).
     */
    public function getBimesterScore($classroomId, $subjectId, $bimester): ?float
    {
        $grades = $this->grades()->whereHas('evaluation', function ($q) use ($classroomId, $subjectId, $bimester) {
            $q->where('classroom_id', $classroomId)
                ->where('subject_id', $subjectId)
                ->where('bimester', $bimester);
        })->with('evaluation')->get();

        if ($grades->isEmpty()) {
            return null;
        }

        $totalScore = $grades->sum('score');
        $totalMax = $grades->sum(fn($g) => $g->evaluation->max_score);

        if ($totalMax == 0) {
            return null;
        }

        // 1. Nota quantitativa de 0 a 10
        $baseScore = ($totalScore / $totalMax) * 10;

        // 2. Busca a avaliação qualitativa lançada
        $bimesterResult = BimesterResult::where('student_id', $this->id)
            ->where('classroom_id', $classroomId)
            ->where('subject_id', $subjectId)
            ->where('bimester', $bimester)
            ->first();

        $qualitative = $bimesterResult ? (float) $bimesterResult->qualitative_eval : 0.0;

        // 3. Aplica o ajuste qualitativo limitando o resultado final entre 0 e 10
        $finalScore = min(10.0, max(0.0, $baseScore + $qualitative));

        return round($finalScore, 1);
    }

    /**
     * Retorna a Avaliação Qualitativa do aluno no bimestre (-1,0 a +1,0)
     */
    public function getQualitativeEval($classroomId, $subjectId, $bimester): float
    {
        $result = BimesterResult::where('student_id', $this->id)
            ->where('classroom_id', $classroomId)
            ->where('subject_id', $subjectId)
            ->where('bimester', $bimester)
            ->first();

        return $result ? (float) $result->qualitative_eval : 0.0;
    }

    /**
     * Calcula o conceito consolidado baseado na nota final com ajuste qualitativo.
     */
    public function getConsolidatedConcept($classroomId, $subjectId, $bimester)
    {
        $finalScore = $this->getBimesterScore($classroomId, $subjectId, $bimester);

        if ($finalScore === null) {
            return '-';
        }

        return $this->calculateGradeConcept($finalScore);
    }

    /**
     * Lógica de conceito com base na nota final (0 a 10)
     */
    public function calculateGradeConcept($score)
    {
        return match (true) {
            $score >= 9.0 => 'A',
            $score >= 7.5 => 'B',
            $score >= 6.0 => 'C',
            $score >= 4.5 => 'D',
            $score >= 3.0 => 'E',
            default       => 'F',
        };
    }

    /**
     * Retorna a nota formatada (ex: "8,5") ou um valor padrão para quando não houver nota.
     */
    public function getFormattedBimesterScore($classroomId, $subjectId, $bimester, $default = '-'): string
    {
        $score = $this->getBimesterScore($classroomId, $subjectId, $bimester);

        return $score !== null ? number_format($score, 1, ',', '.') : $default;
    }
}
