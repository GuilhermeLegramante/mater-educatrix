<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\DescriptiveQuestion;
use App\Models\DescriptiveRating;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ReportCardController extends Controller
{
    public function generatePDF(Request $request, Classroom $classroom, Student $student)
    {
        $settings = \App\Models\SchoolSetting::first();
        $relationsToLoad = [];

        // 1. Carregamento de Notas e Faltas
        if ($request->has('include_grades')) {
            $relationsToLoad['grades.evaluation'] = function ($query) use ($classroom) {
                $query->where('classroom_id', $classroom->id);
            };
            $relationsToLoad['bimesterResults'] = function ($query) use ($classroom) {
                $query->where('classroom_id', $classroom->id);
            };

            $relationsToLoad['attendances'] = function ($query) use ($classroom) {
                $query->where('is_absent', true)
                    ->whereHas('schoolDay', function ($q) use ($classroom) {
                        $q->where('classroom_id', $classroom->id);
                    })->with('schoolDay');
            };
        }

        // 2. Relatos de Preceptoria
        if ($request->has('include_preceptory')) {
            $relationsToLoad['preceptoryReports'] = function ($query) use ($classroom) {
                $query->where('classroom_id', $classroom->id)
                    ->with('subject');
            };
        }

        // 3. Ocorrências e Atendimentos do Estudante
        if ($request->has('include_occurrences')) {
            $relationsToLoad['occurrences'] = function ($query) {
                $query->with(['type', 'user'])->orderBy('date', 'desc');
            };
        }

        // Executa Lazy Loading otimizado
        if (!empty($relationsToLoad)) {
            $student->load($relationsToLoad);
        }

        // 4. Carregamento da AVALIAÇÃO DESCRITIVA (Matriz de Perguntas e Respostas)
        $descriptiveData = null;
        if ($request->has('include_descriptive_evaluation')) {
            // Busca perguntas ativas agrupadas por disciplina
            $questions = DescriptiveQuestion::get();

            // Busca as respostas gravadas para este aluno no ano da turma
            $evaluations = DescriptiveRating::where('student_id', $student->id)
                ->where('year', $classroom->year)
                ->get()
                ->keyBy(function ($item) {
                    return $item->descriptive_question_id . '_' . $item->bimester;
                });

            $descriptiveData = [
                'questions'   => $questions->groupBy('subject_id'),
                'evaluations' => $evaluations
            ];
        }

        // Helper de Contagem de Faltas
        $getAbsencesCount = function ($subjectId, $bimester) use ($student, $settings) {
            if (!$settings) return 0;
            $period = $settings->getBimesterPeriod($bimester);
            if (!$period['start'] || !$period['end']) return 0;

            return $student->attendances
                ->where('subject_id', $subjectId)
                ->filter(function ($attendance) use ($period) {
                    return $attendance->schoolDay->date->between($period['start'], $period['end']);
                })
                ->sum('quantity');
        };

        $tutor = '';

        if ($classroom->name == '1º Ano') {
            $tutor = 'elisa';
        } elseif ($classroom->name == '2º Ano') {
            $tutor = 'simone';
        } elseif ($classroom->name == '3º Ano') {
            $tutor = 'maiara';
        } elseif ($classroom->name == '4º Ano') {
            $tutor = 'rafaella';
        } elseif ($classroom->name == '5º Ano') {
            $tutor = 'graziele';
        } elseif ($classroom->name == '6º Ano') {
            $tutor = 'joelson';
        } elseif ($classroom->name == '7º Ano') {
            $tutor = 'luma';
        } elseif ($classroom->name == '8º Ano') {
            $tutor = 'pablo';
        }

        $data = [
            'classroom'                 => $classroom,
            'student'                   => $student,
            'subjects'                  => $classroom->subjects,
            'date'                      => now()->format('d/m/Y'),
            'settings'                  => $settings,
            'getAbsencesCount'          => $getAbsencesCount,
            'showGrades'                => $request->has('include_grades'),
            'showPreceptory'            => $request->has('include_preceptory'),
            'showOccurrences'           => $request->has('include_occurrences'),
            'showDescriptiveEvaluation' => $request->has('include_descriptive_evaluation'),
            'descriptiveData'           => $descriptiveData,
            'tutor'                     => $tutor,
        ];

        $pdf = Pdf::loadView('pdf.report-card', $data)->setPaper('a4', 'portrait');
        return $pdf->stream("boletim_{$student->name}_{$classroom->name}.pdf");
    }

    public function batchIndex(Request $request)
    {
        $user = auth()->user();

        // Busca o ano letivo atual configurado no sistema ou usa o ano corrente
        $settings = \App\Models\SchoolSetting::first();
        $currentYear = $settings?->current_year ?? now()->year;

        if (!$user->isAdmin()) {
            $teacherClassroomIds = $user->classrooms()->pluck('classrooms.id')->toArray();

            $classrooms = \App\Models\Classroom::whereIn('id', $teacherClassroomIds)
                ->where('year', $currentYear)
                ->orderBy('name')
                ->get();
        } else {
            $classrooms = \App\Models\Classroom::where('year', $currentYear)
                ->orderBy('name')
                ->get();
        }

        $selectedClassroomId = $request->get('classroom_id');
        $students = collect();

        if ($selectedClassroomId) {
            $students = Student::whereHas('classrooms', function ($q) use ($selectedClassroomId) {
                $q->where('classrooms.id', $selectedClassroomId);
            })->orderBy('name')->get();
        }

        return view('reports.report-cards.batch', compact('classrooms', 'selectedClassroomId', 'students'));
    }

    public function generateBatchPDF(Request $request)
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $request->validate([
            'scope' => 'required|in:student,classroom,all',
            'classroom_id' => 'required_if:scope,student,classroom',
            'student_id' => 'required_if:scope,student',
        ]);

        $query = Student::query();

        if ($request->scope === 'student') {
            $query->where('id', $request->student_id);
        } elseif ($request->scope === 'classroom') {
            $query->whereHas('classrooms', function ($q) use ($request) {
                $q->where('classrooms.id', $request->classroom_id);
            });
        }

        // Carrega os estudantes com as turmas e disciplinas para otimizar
        $students = $query->with(['classrooms.subjects'])->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'Nenhum estudante encontrado para os filtros selecionados.');
        }

        $settings = \App\Models\SchoolSetting::first();
        $date = now()->format('d/m/Y');

        // Se for apenas 1 aluno, mantém o fluxo normal de download direto do PDF
        if ($students->count() === 1) {
            $student = $students->first();
            $classroom = $request->filled('classroom_id')
                ? Classroom::find($request->classroom_id)
                : $student->classrooms->first();

            if (!$classroom) {
                return back()->with('error', 'O estudante não possui turma ativa vinculada.');
            }
            return $this->generatePDF($request, $classroom, $student);
        }

        // Garante que o diretório public da storage existe
        $storageDirectory = storage_path('app/public');
        if (!file_exists($storageDirectory)) {
            mkdir($storageDirectory, 0755, true);
        }

        // Cria e salva o ZIP na storage
        $zipFileName = 'boletins_' . now()->format('Y-m-d_His') . '.zip';
        $zipPath = $storageDirectory . '/' . $zipFileName;

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {

            foreach ($students as $student) {
                // Define a turma corretamente com base no filtro ou na turma ativa do aluno
                $classroom = $request->filled('classroom_id')
                    ? Classroom::find($request->classroom_id)
                    : $student->classrooms->first();

                if (!$classroom) continue;

                $relationsToLoad = [];
                if ($request->has('include_grades')) {
                    $relationsToLoad['grades.evaluation'] = function ($q) use ($classroom) {
                        $q->where('classroom_id', $classroom->id);
                    };
                    $relationsToLoad['bimesterResults'] = function ($q) use ($classroom) {
                        $q->where('classroom_id', $classroom->id);
                    };
                    $relationsToLoad['attendances'] = function ($q) use ($classroom) {
                        $q->where('is_absent', true)
                            ->whereHas('schoolDay', function ($sq) use ($classroom) {
                                $sq->where('classroom_id', $classroom->id);
                            })->with('schoolDay');
                    };
                }
                if ($request->has('include_preceptory')) {
                    $relationsToLoad['preceptoryReports'] = function ($q) use ($classroom) {
                        $q->where('classroom_id', $classroom->id)->with('subject');
                    };
                }
                if ($request->has('include_occurrences')) {
                    $relationsToLoad['occurrences'] = function ($q) {
                        $q->with(['type', 'user'])->orderBy('date', 'desc');
                    };
                }

                if (!empty($relationsToLoad)) {
                    $student->load($relationsToLoad);
                }

                $tutor = '';
                if ($classroom->name == '1º Ano') {
                    $tutor = 'elisa';
                } elseif ($classroom->name == '2º Ano') {
                    $tutor = 'simone';
                } elseif ($classroom->name == '3º Ano') {
                    $tutor = 'maiara';
                } elseif ($classroom->name == '4º Ano') {
                    $tutor = 'rafaella';
                } elseif ($classroom->name == '5º Ano') {
                    $tutor = 'graziele';
                } elseif ($classroom->name == '6º Ano') {
                    $tutor = 'joelson';
                } elseif ($classroom->name == '7º Ano') {
                    $tutor = 'luma';
                } elseif ($classroom->name == '8º Ano') {
                    $tutor = 'pablo';
                }

                $getAbsencesCount = function ($subjectId, $bimester) use ($student, $settings) {
                    if (!$settings) return 0;
                    $period = $settings->getBimesterPeriod($bimester);
                    if (!$period['start'] || !$period['end']) return 0;

                    return $student->attendances
                        ->where('subject_id', $subjectId)
                        ->filter(function ($attendance) use ($period) {
                            return $attendance->schoolDay->date->between($period['start'], $period['end']);
                        })
                        ->sum('quantity');
                };

                $data = [
                    'classroom'                 => $classroom,
                    'student'                   => $student,
                    'subjects'                  => $classroom->subjects,
                    'date'                      => $date,
                    'settings'                  => $settings,
                    'getAbsencesCount'          => $getAbsencesCount,
                    'showGrades'                => $request->has('include_grades'),
                    'showPreceptory'            => $request->has('include_preceptory'),
                    'showOccurrences'           => $request->has('include_occurrences'),
                    'showDescriptiveEvaluation' => $request->has('include_descriptive_evaluation'),
                    'descriptiveData'           => null,
                    'tutor'                     => $tutor,
                ];

                $pdf = Pdf::loadView('pdf.report-card', $data)->setPaper('a4', 'portrait');
                $pdfContent = $pdf->output();

                $safeStudentName = Str::slug($student->name, '_');
                $safeClassroomName = Str::slug($classroom->name, '_');
                $fileName = "boletim_{$safeStudentName}_{$safeClassroomName}.pdf";

                $zip->addFromString($fileName, $pdfContent);
            }

            $zip->close();

            // Retorna o download do arquivo compactado gerado
            return response()->download($zipPath, $zipFileName, [
                'Content-Type' => 'application/zip',
            ])->deleteFileAfterSend(true);
        } else {
            return back()->with('error', 'Não foi possível criar o arquivo compactado de boletins.');
        }
    }
}
