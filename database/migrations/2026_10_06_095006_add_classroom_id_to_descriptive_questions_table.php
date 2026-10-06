<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('descriptive_questions', function (Blueprint $table) {
            // Adiciona a chave estrangeira para classroom_id (permitindo null caso seja uma questão geral)
            $table->foreignId('classroom_id')
                ->nullable()
                ->after('subject_id')
                ->constrained('classrooms')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('descriptive_questions', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropColumn('classroom_id');
        });
    }
};
