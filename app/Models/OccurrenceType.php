<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OccurrenceType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'is_active'];

    public function occurrences()
    {
        return $this->hasMany(Occurrence::class);
    }
}
