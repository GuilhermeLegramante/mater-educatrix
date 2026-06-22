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
        Schema::table('school_settings', function (Blueprint $table) {
            // Adiciona os campos de data após a coluna active_bimester
            $table->date('bimester_1_start')->nullable()->after('active_bimester');
            $table->date('bimester_1_end')->nullable()->after('bimester_1_start');

            $table->date('bimester_2_start')->nullable()->after('bimester_1_end');
            $table->date('bimester_2_end')->nullable()->after('bimester_2_start');

            $table->date('bimester_3_start')->nullable()->after('bimester_2_end');
            $table->date('bimester_3_end')->nullable()->after('bimester_3_start');

            $table->date('bimester_4_start')->nullable()->after('bimester_3_end');
            $table->date('bimester_4_end')->nullable()->after('bimester_4_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_settings', function (Blueprint $table) {
            // Remove as colunas caso você precise dar um rollback na migration
            $table->dropColumn([
                'bimester_1_start',
                'bimester_1_end',
                'bimester_2_start',
                'bimester_2_end',
                'bimester_3_start',
                'bimester_3_end',
                'bimester_4_start',
                'bimester_4_end',
            ]);
        });
    }
};
