<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'current_year',
        'active_bimester',
        // Novas colunas de controle temporal:
        'bimester_1_start',
        'bimester_1_end',
        'bimester_2_start',
        'bimester_2_end',
        'bimester_3_start',
        'bimester_3_end',
        'bimester_4_start',
        'bimester_4_end',
    ];

    protected $casts = [
        'bimester_1_start' => 'date',
        'bimester_1_end' => 'date',
        'bimester_2_start' => 'date',
        'bimester_2_end' => 'date',
        'bimester_3_start' => 'date',
        'bimester_3_end' => 'date',
        'bimester_4_start' => 'date',
        'bimester_4_end' => 'date',
    ];

    /**
     * Helper prático para retornar o intervalo de um bimestre específico
     */
    public function getBimesterPeriod(int $bimester): ?array
    {
        if ($bimester < 1 || $bimester > 4) return null;

        return [
            'start' => $this->{"bimester_{$bimester}_start"},
            'end'   => $this->{"bimester_{$bimester}_end"}
        ];
    }
}
