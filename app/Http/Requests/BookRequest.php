<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'              => ['required', 'string', 'max:255'],
            'author'             => ['required', 'string', 'max:255'],
            'isbn'               => ['nullable', 'string', 'max:50'],
            'publisher'          => ['nullable', 'string', 'max:255'],
            'publication_year'   => ['nullable', 'string', 'max:50'],
            'publication_city'   => ['nullable', 'string', 'max:255'],
            'first_edition_year' => ['nullable', 'string', 'max:50'],
            'type'               => ['required', 'string', 'max:100'],
            'discipline'         => ['nullable', 'string', 'max:100'],
            'location_shelf'     => ['nullable', 'string', 'max:255'],
            'status'             => ['required', 'in:available,borrowed,reserved,maintenance'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'  => 'O título do livro é obrigatório.',
            'author.required' => 'O nome do autor é obrigatório.',
            'type.required'   => 'Selecione o tipo de livro.',
            'status.required' => 'O status do livro é obrigatório.',
        ];
    }
}
