<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOccurrenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'occurrence_type_id' => 'required|exists:occurrence_types,id',
            'classroom_id'       => 'nullable|exists:classrooms,id',
            'date'               => 'required|date|before_or_equal:today',
            'time'               => 'nullable|date_format:H:i',
            'description'        => 'required|string|min:10',
            'actions_taken'      => 'nullable|string',
        ];
    }
}
