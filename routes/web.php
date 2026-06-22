<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\PreceptoryController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SchoolSettingController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\OccurrenceController;
use App\Http\Controllers\OccurrenceTypeController;


require __DIR__ . '/auth.php';

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Dashboard e Turmas
    // Route::get('/', [ClassroomController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('classrooms', ClassroomController::class);



    // Ações específicas da Turma
    Route::post('/classrooms/{classroom}/enroll', [ClassroomController::class, 'enroll'])
        ->name('classrooms.enroll');
    Route::post('classrooms/{classroom}/subjects', [ClassroomController::class, 'attachSubject'])->name('classrooms.attachSubject');
    Route::post('classrooms/{classroom}/concepts', [ClassroomController::class, 'updateConcept'])->name('concepts.update');

    Route::resource('evaluations', EvaluationController::class);

    // Gestão de Alunos e Disciplinas
    Route::resource('students', StudentController::class);
    Route::resource('subjects', SubjectController::class);

    Route::put('/classrooms/{classroom}/curriculum', [ClassroomController::class, 'updateCurriculum'])
        ->name('classrooms.curriculum.update');

    // Escopo da Turma (Avaliações e Preceptoria)
    Route::prefix('classrooms/{classroom}')->group(function () {
        // Lançamento de Notas
        Route::get('evaluations/{evaluation}/grades', [GradeController::class, 'create'])->name('grades.create');
        Route::post('evaluations/{evaluation}/grades', [GradeController::class, 'store'])->name('grades.store');

        // Preceptoria
        Route::resource('preceptory', PreceptoryController::class);
    });

    Route::get('/classrooms/{classroom}/students/{student}/report-card-pdf', [ReportCardController::class, 'generatePDF'])
        ->name('students.report-card.pdf');

    // Gestão de Conceitos Bimestrais
    Route::post('classrooms/{classroom}/concepts', [ClassroomController::class, 'updateConcept'])->name('concepts.update');

    // Configurações da Escola
    Route::get('/settings/school', [SchoolSettingController::class, 'edit'])
        ->name('settings.school');

    Route::put('/settings/school', [SchoolSettingController::class, 'update'])
        ->name('settings.school.update');


    // 1. Rota Genérica (Nomeada para o Menu): Tela de seleção de turmas
    Route::get('/diarios', [AttendanceController::class, 'dashboard'])->name('attendance.index');

    // 2. Tela do Grid de Frequência de uma turma e disciplina específica
    Route::get('/diario/{classroom_id}/{subject_id}', [AttendanceController::class, 'index'])->name('attendance.show');

    // 3. Rota API/AJAX Inline para salvar a falta sem dar reload
    Route::post('/diario/toggle', [AttendanceController::class, 'toggle'])->name('attendance.toggle');

    // Tela com o formulário de geração e listagem resumida
    Route::get('/calendario', [CalendarController::class, 'index'])->name('admin.calendar.index');

    // Processamento da geração automática de dias
    Route::post('/calendario/gerar', [CalendarController::class, 'generate'])->name('admin.calendar.generate');

    // Ação rápida para deletar um dia específico (caso de emenda de feriado)
    Route::delete('/calendario/dia/{id}', [CalendarController::class, 'destroy'])->name('admin.calendar.day.destroy');

    // Ação para limpar todo o calendário do ano (caso de mudança de ano letivo ou erro na geração)
    Route::delete('/calendario/limpar-ano', [CalendarController::class, 'clearYear'])->name('admin.calendar.clearYear');



    // Rota customizada para alternar o status (deve vir antes ou junto ao resource)
    Route::patch('occurrence-types/{occurrence_type}/toggle', [OccurrenceTypeController::class, 'toggleStatus'])
        ->name('occurrence-types.toggle');

    // Painel de Configuração dos Tipos de Ocorrência (Apenas Gestores)
    Route::resource('occurrence-types', OccurrenceTypeController::class)
        ->except(['show'])
        ->names('occurrence-types');

    // Rotas de Ocorrências vinculadas ao Aluno
    Route::get('students/{student}/occurrences/create', [OccurrenceController::class, 'create'])->name('students.occurrences.create');
    Route::post('students/{student}/occurrences', [OccurrenceController::class, 'store'])->name('students.occurrences.store');
    Route::delete('occurrences/{occurrence}', [OccurrenceController::class, 'destroy'])->name('occurrences.destroy');
});
