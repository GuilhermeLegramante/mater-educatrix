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
     *
     * A busca ignora:
     * - Maiúsculas/minúsculas
     * - Acentos
     * - Hífens
     * - Espaços
     * - Pontuação
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($q, $search) {

            // Normaliza o termo pesquisado
            $search = mb_strtolower(trim($search), 'UTF-8');

            // Remove acentos
            $search = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $search);

            // Remove tudo que não for letra ou número
            $search = preg_replace('/[^a-z0-9]/', '', $search);

            // Só executa a busca se ainda houver conteúdo
            if (empty($search)) {
                return;
            }

            $q->where(function ($sub) use ($search) {

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

                    /*
                 * Normaliza o campo do banco:
                 *
                 * LOWER()          -> minúsculas
                 * REPLACE()        -> remove espaços
                 * REPLACE()        -> remove hífens
                 * REPLACE()        -> remove pontuação
                 *
                 * A collation utf8mb4_general_ci faz a comparação
                 * ignorando diferenças de maiúsculas/minúsculas
                 * e acentuação.
                 */
                    $normalizedField = "
                    LOWER(
                        REPLACE(
                            REPLACE(
                                REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    REPLACE(
                                                        {$field},
                                                        ' ', ''
                                                    ),
                                                    '-', ''
                                                ),
                                                '–', ''
                                            ),
                                            '—', ''
                                        ),
                                        '.', ''
                                    ),
                                    ',', ''
                                ),
                                ':', ''
                            ),
                            ';', ''
                        )
                    )
                ";

                    $sub->orWhereRaw(
                        "{$normalizedField} COLLATE utf8mb4_general_ci LIKE ?",
                        ["%{$search}%"]
                    );
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
