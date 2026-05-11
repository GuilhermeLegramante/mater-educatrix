<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\PreceptoryController;
use App\Http\Controllers\SubjectController;


// Dashboard e Turmas
Route::get('/', [ClassroomController::class, 'index'])->name('dashboard');
Route::resource('classrooms', ClassroomController::class);

// Ações específicas da Turma
Route::post('classrooms/{classroom}/enroll', [ClassroomController::class, 'enroll'])->name('classrooms.enroll');
Route::post('classrooms/{classroom}/subjects', [ClassroomController::class, 'attachSubject'])->name('classrooms.attachSubject');
Route::post('classrooms/{classroom}/concepts', [ClassroomController::class, 'updateConcept'])->name('concepts.update');

Route::resource('evaluations', EvaluationController::class);

// Gestão de Alunos e Disciplinas
Route::resource('students', StudentController::class);
Route::resource('subjects', SubjectController::class);

// Escopo da Turma (Avaliações e Preceptoria)
Route::prefix('classrooms/{classroom}')->group(function () {
    // Lançamento de Notas
    Route::get('evaluations/{evaluation}/grades', [GradeController::class, 'create'])->name('grades.create');
    Route::post('evaluations/{evaluation}/grades', [GradeController::class, 'store'])->name('grades.store');

    // Preceptoria
    Route::resource('preceptory', PreceptoryController::class);
});

// Gestão de Conceitos Bimestrais
Route::post('classrooms/{classroom}/concepts', [ClassroomController::class, 'updateConcept'])->name('concepts.update');
