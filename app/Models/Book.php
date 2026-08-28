<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'author',
        'publisher',
        'publication_year',
        'publication_city',
        'first_edition_year',
        'type',
        'discipline',
        'location_shelf',
        'status',
        'is_printed',
    ];

    /**
     * Scope para busca avançada no acervo.
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%");
            });
        });

        $query->when($filters['type'] ?? null, function ($q, $type) {
            $q->where('type', $type);
        });

        $query->when($filters['discipline'] ?? null, function ($q, $discipline) {
            $q->where('discipline', $discipline);
        });
    }
}
