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
        Schema::create('occurrence_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: Atendimento na Enfermaria, Indisciplina, Elogio, Atraso
            $table->string('color')->default('slate'); // Para renderizar badges coloridos no front (ex: red, yellow, green)
            $table->boolean('is_active')->default(true); // Permite desativar um tipo sem apagar o histórico
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('occurrence_types');
    }
};
