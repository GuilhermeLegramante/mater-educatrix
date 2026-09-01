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

            // Normaliza o texto pesquisado
            $search = mb_strtolower(trim($search), 'UTF-8');

            // Remove acentos
            $search = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $search);

            // Todos os tipos de hífen viram espaço
            $search = str_replace(['-', '–', '—'], ' ', $search);

            // Remove caracteres especiais
            $search = preg_replace('/[^a-z0-9\s]/', ' ', $search);

            // Remove espaços duplicados
            $search = preg_replace('/\s+/', ' ', trim($search));

            // Divide a pesquisa em palavras
            $terms = explode(' ', $search);

            $q->where(function ($sub) use ($terms) {

                foreach ($terms as $term) {

                    if (empty($term)) {
                        continue;
                    }

                    /*
                 * Cada palavra pesquisada precisa aparecer em pelo menos
                 * um dos campos.
                 */
                    $sub->where(function ($wordQuery) use ($term) {

                        $like = "%{$term}%";

                        foreach (
                            [
                                'title',
                                'author',
                                'isbn',
                                'publisher',
                                'type',
                                'discipline',
                            ] as $field
                        ) {

                            $wordQuery->orWhereRaw(
                                "REPLACE(
                                LOWER({$field}),
                                '-',
                                ' '
                            ) COLLATE utf8mb4_general_ci LIKE ?",
                                [$like]
                            );
                        }
                    });
                }
            });
        });

        // Filtro por tipo
        $query->when($filters['type'] ?? null, function ($q, $type) {
            $q->where('type', $type);
        });

        // Filtro por disciplina
        $query->when($filters['discipline'] ?? null, function ($q, $discipline) {
            $q->where('discipline', $discipline);
        });
    }
}
