<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreceptoryReport extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'bimester', 'content'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Retorna a disciplina vinculada ao relato.
     * Pode ser nulo se for "Desenvolvimento Geral".
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
