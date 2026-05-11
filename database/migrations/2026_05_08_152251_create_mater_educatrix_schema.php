<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Alunos
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('registration_number')->unique();
            $table->timestamps();
        });

        // 2. Disciplinas Globais (O catálogo de matérias)
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: Latim, Matemática, Gramática Portuguesa
            $table->integer('workload')->nullable();
            $table->timestamps();
        });

        // 3. Turmas (O eixo central: Ano e Nome)
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: 5º Ano A, 1ª Série Médio
            $table->year('year');   // Ex: 2024
            $table->timestamps();
        });

        // 4. Matrículas (Histórico: Liga Aluno à Turma e define se ele passou ou não)
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'completed', 'failed'])->default('active');
            $table->timestamps();
        });

        // 5. Grade Curricular da Turma (Define quais disciplinas aquela turma específica terá)
        Schema::create('classroom_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // 6. Avaliações (Sempre vinculadas a uma Turma e uma Disciplina desta turma)
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('weight', 5, 2); // Peso da avaliação
            $table->integer('max_score');    // Pontuação máxima (ex: 100 pontos)
            $table->integer('bimester');     // 1, 2, 3 ou 4
            $table->date('applied_at')->nullable();
            $table->timestamps();
        });

        // 7. Notas (Scores brutos dos alunos por avaliação)
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluation_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 8, 2); // Pontos obtidos (ex: 85.50)
            $table->timestamps();
        });

        // 8. Preceptoria (Relatórios qualitativos por Turma e opcionalmente por Disciplina)
        Schema::create('preceptory_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('classroom_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('bimester');
            $table->text('content');        // O parecer pedagógico
            $table->text('virtues_noted')->nullable(); // Virtudes observadas no aluno
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Ordem inversa para evitar erros de integridade ao apagar
        Schema::dropIfExists('preceptory_reports');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('classroom_subject');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('students');
    }
};
