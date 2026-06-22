<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolDay extends Model
{
    protected $fillable = ['classroom_id', 'date'];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Define que o dia letivo pertence a uma turma específica.
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
    
    /**
     * Define a relação de um dia letivo com as faltas registradas.
     * Permite acessar as faltas diretamente a partir do dia letivo.
     */
    public function absences(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
