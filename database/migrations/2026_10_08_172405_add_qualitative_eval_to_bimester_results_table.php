<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bimester_results', function (Blueprint $table) {
            // Torna o conceito opcional/calculado e adiciona a nota qualitativa (-1.00 a +1.00)
            $table->string('concept', 2)->nullable()->change();
            $table->decimal('qualitative_eval', 3, 1)->default(0.0)->after('subject_id');
        });
    }

    public function down(): void
    {
        Schema::table('bimester_results', function (Blueprint $table) {
            $table->dropColumn('qualitative_eval');
        });
    }
};
