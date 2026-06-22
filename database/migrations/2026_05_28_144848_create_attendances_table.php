<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Controle de faltas dos alunos em dias específicos de aula
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_day_id')->constrained()->onDelete('cascade');

            // NOVA COLUNA: Vincula a falta à disciplina específica
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');

            $table->boolean('is_absent')->default(true);
            $table->timestamps();

            // Garante que não haja registros duplicados para o mesmo aluno, no mesmo dia e na mesma matéria
            $table->unique(['student_id', 'school_day_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
