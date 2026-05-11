<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 3. Resultados Bimestrais (Conceitos Editáveis)
        Schema::create('bimester_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('classroom_id')->constrained();
            $table->foreignId('subject_id')->constrained();
            $table->integer('bimester');
            $table->string('concept', 2)->nullable(); // A, B, C...
            $table->decimal('average_score', 5, 2)->nullable(); // Média real calculada
            $table->text('teacher_note')->nullable(); // Para observações do professor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimester_results');
    }
};
