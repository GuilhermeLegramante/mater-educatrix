<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('descriptive_questions', function (Blueprint $table) {
            $table->id();
            // Se for nula, assume-se que é uma pergunta geral de "Caráter/Conduta"
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('question_text');
            $table->integer('order_index')->default(0); // Para ordenar as perguntas na planilha
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('descriptive_questions');
    }
};
