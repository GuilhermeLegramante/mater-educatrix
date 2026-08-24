<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DescriptiveQuestion extends Model
{
    protected $fillable = ['subject_id', 'question_text', 'order_index'];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
