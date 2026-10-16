<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Services\ReportCardService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ReportCardController extends Controller
{
    public function __construct(protected ReportCardService $reportCardService) {}

    public function generatePDF(Request $request, Classroom $classroom, Student $student)
    {
        $settings = \App\Models\SchoolSetting::first();

        $this->reportCardService->loadRelations($student, $classroom, $request);

        $descriptiveData = $request->has('include_descriptive_evaluation')
            ? $this->reportCardService->getDescriptiveEvaluationData($student, $classroom)
            : null;

        $data = $this->reportCardService->getPayload($classroom, $student, $request, $settings, $descriptiveData);

        return Pdf::loadView('pdf.report-card', $data)
            ->setPaper('a4', 'portrait')
            ->stream("boletim_{$student->name}_{$classroom->name}.pdf");
    }

    public function batchIndex(Request $request)
    {
        $user = auth()->user();
        $settings = \App\Models\SchoolSetting::first();
        $currentYear = $settings?->current_year ?? now()->year;

        $classroomsQuery = Classroom::where('year', $currentYear)->orderBy('name');
        if (!$user->isAdmin()) {
            $classroomsQuery->whereIn('id', $user->classrooms()->pluck('classrooms.id'));
        }
        $classrooms = $classroomsQuery->get();

        $selectedClassroomId = $request->get('classroom_id');
        $students = $selectedClassroomId
            ? Student::whereHas('classrooms', fn($q) => $q->where('classrooms.id', $selectedClassroomId))->orderBy('name')->get()
            : collect();

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
            $query->whereHas('classrooms', fn($q) => $q->where('classrooms.id', $request->classroom_id));
        }

        $students = $query->with(['classrooms.subjects'])->get();
        if ($students->isEmpty()) {
            return back()->with('error', 'Nenhum estudante encontrado para os filtros selecionados.');
        }

        $settings = \App\Models\SchoolSetting::first();

        // Se for apenas 1 aluno, redireciona para o fluxo padrão
        if ($students->count() === 1) {
            $student = $students->first();
            $classroom = $request->filled('classroom_id') ? Classroom::find($request->classroom_id) : $student->classrooms->first();
            if (!$classroom) return back()->with('error', 'O estudante não possui turma ativa vinculada.');
            return $this->generatePDF($request, $classroom, $student);
        }

        $storageDirectory = storage_path('app/public');
        if (!file_exists($storageDirectory)) mkdir($storageDirectory, 0755, true);

        $zipFileName = 'boletins_' . now()->format('Y-m-d_His') . '.zip';
        $zipPath = $storageDirectory . '/' . $zipFileName;

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($students as $student) {
                $classroom = $request->filled('classroom_id') ? Classroom::find($request->classroom_id) : $student->classrooms->first();
                if (!$classroom) continue;

                $this->reportCardService->loadRelations($student, $classroom, $request);
                $data = $this->reportCardService->getPayload($classroom, $student, $request, $settings);

                $pdfContent = Pdf::loadView('pdf.report-card', $data)->setPaper('a4', 'portrait')->output();
                $zip->addFromString("boletim_" . Str::slug($student->name, '_') . "_" . Str::slug($classroom->name, '_') . ".pdf", $pdfContent);
            }
            $zip->close();

            return response()->download($zipPath, $zipFileName, ['Content-Type' => 'application/zip'])->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Não foi possível criar o arquivo compactado de boletins.');
    }
}
