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
        Schema::create('occurrences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('occurrence_type_id')->constrained()->onDelete('restrict'); // Impede apagar o tipo se houver registros
            $table->foreignId('user_id')->nullable()->constrained(); // Gestor ou professor que registrou a ocorrência
            $table->foreignId('classroom_id')->nullable()->constrained(); // Opcional: para saber em qual turma o aluno estava no momento

            $table->date('date'); // Data do fato
            $table->time('time')->nullable(); // Horário do fato
            $table->text('description'); // Relato detalhado do que aconteceu
            $table->text('actions_taken')->nullable(); // Providências tomadas (Ex: Ligado para os pais, suspenso por 1 dia, medicado)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('occurrences');
    }
};
