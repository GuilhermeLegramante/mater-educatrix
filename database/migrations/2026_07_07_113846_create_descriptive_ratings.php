<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('descriptive_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('descriptive_question_id')->constrained()->onDelete('cascade');

            // Contexto da avaliação
            $table->integer('year');
            $table->integer('bimester'); // 1, 2, 3 ou 4

            // Valor da resposta: Muito bem, Em parte, Não / Sim, Às vezes, Raramente
            // Salvamos uma string padronizada ('optimal', 'partial', 'critical') para unificar as duas matrizes
            $table->string('rating', 20);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('descriptive_ratings');
    }
};
