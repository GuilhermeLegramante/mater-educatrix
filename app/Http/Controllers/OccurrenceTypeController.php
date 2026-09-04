<?php

namespace App\Http\Controllers;

use App\Models\OccurrenceType;
use App\Http\Requests\StoreOccurrenceTypeRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OccurrenceTypeController extends Controller
{
    public function index()
    {
        $types = OccurrenceType::orderBy('name')->get();
        return view('occurrence-types.index', compact('types'));
    }

    public function store(StoreOccurrenceTypeRequest $request)
    {
        OccurrenceType::create($request->validated());

        return redirect()->route('occurrence-types.index')
            ->with('success', 'Tipo de ocorrência cadastrado com sucesso!');
    }

    public function update(Request $request, OccurrenceType $occurrenceType)
    {
        // Validação correta garantindo que a regra UNIQUE ignore apenas o ID atual
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('occurrence_types', 'name')->ignore($occurrenceType->id),
            ],
            'color' => ['required', 'string', 'max:7'],
        ]);

        // Atualização dos dados no banco
        $occurrenceType->update($validated);

        return redirect()->back()->with('success', 'Tipo de ocorrência atualizado com sucesso!');
    }

    public function toggleStatus(OccurrenceType $occurrenceType)
    {
        $occurrenceType->update(['is_active' => !$occurrenceType->is_active]);

        return redirect()->route('occurrence-types.index')
            ->with('success', 'Status do tipo de ocorrência alterado!');
    }

    /**
     * Remove o tipo de ocorrência do sistema
     */
    public function destroy(OccurrenceType $occurrenceType)
    {
        try {
            $occurrenceType->delete();

            return redirect()->route('occurrence-types.index')
                ->with('success', 'Tipo de ocorrência excluído com sucesso!');
        } catch (\Exception $e) {
            // Captura o erro caso o banco barre a exclusão por ter ocorrências vinculadas a alunos
            return redirect()->route('occurrence-types.index')
                ->with('error', 'Não é possível excluir esta categoria porque existem ocorrências de alunos vinculadas a ela. Você pode desativá-la em vez de excluir.');
        }
    }
}
