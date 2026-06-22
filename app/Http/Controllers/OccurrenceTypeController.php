<?php

namespace App\Http\Controllers;

use App\Models\OccurrenceType;
use App\Http\Requests\StoreOccurrenceTypeRequest;

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

    public function update(StoreOccurrenceTypeRequest $request, OccurrenceType $occurrenceType)
    {
        $occurrenceType->update($request->validated());

        return redirect()->route('occurrence-types.index')
            ->with('success', 'Tipo de ocorrência atualizado com sucesso!');
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
