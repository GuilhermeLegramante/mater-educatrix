<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOccurrenceTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Altere para auth()->user()->is_admin se tiver controle de nível de acesso
    }

    public function rules(): array
    {
        return [
            'name'  => 'required|string|max:255|unique:occurrence_types,name,' . $this->route('occurrence_type'),
            'color' => 'required|string|in:slate,gray,zinc,neutral,stone,red,orange,amber,yellow,lime,green,emerald,teal,cyan,sky,blue,indigo,violet,purple,fuchsia,pink,rose',
        ];
    }
}
