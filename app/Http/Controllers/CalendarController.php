<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\SchoolDay;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        // Pega as turmas para exibição ou filtros
        $classrooms = Classroom::orderBy('name')->get();

        // Pega os últimos 15 dias gerados para o administrador ver o que já existe
        $recentDays = SchoolDay::with('classroom')
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('admin.calendar.index', compact('classrooms', 'recentDays'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2024|max:2030',
            'month' => 'required|integer|min:1|max:12',
            'classroom_ids' => 'required|array',
            'classroom_ids.*' => 'exists:classrooms,id'
        ]);

        $year = $request->year;
        $month = $request->month;
        $classroomIds = $request->classroom_ids;

        // Define o início e o fim do mês selecionado
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Cria o período dia a dia
        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);

        // Lista de feriados nacionais fixos (Mês-Dia)
        $feriadosFixos = [
            '01-01',
            '04-21',
            '05-01',
            '09-07',
            '10-12',
            '11-02',
            '11-15',
            '12-25'
        ];

        $diasCriados = 0;

        foreach ($classroomIds as $classroomId) {
            foreach ($period as $date) {
                // 1. Pula Sábados e Domingos
                if ($date->isWeekend()) {
                    continue;
                }

                // 2. Pula Feriados Nacionais Fixos
                if (in_array($date->format('m-d'), $feriadosFixos)) {
                    continue;
                }

                // 3. Insere se não existir para aquela turma naquele dia
                $created = SchoolDay::firstOrCreate([
                    'classroom_id' => $classroomId,
                    'date' => $date->format('Y-m-d'),
                ]);

                if ($created->wasRecentlyCreated) {
                    $diasCriados++;
                }
            }
        }

        return redirect()->route('admin.calendar.index')
            ->with('success', "Calendário gerado com sucesso! {$diasCriados} novos dias letivos foram criados.");
    }

    public function destroy($id)
    {
        $day = SchoolDay::findOrFail($id);
        $day->delete();

        return redirect()->route('admin.calendar.index')
            ->with('success', 'Dia letivo removido do calendário.');
    }

    /**
     * Remove todos os dias letivos de um ano específico
     */
    public function clearYear(Request $request)
    {
        $request->validate([
            'confirm_year' => 'required|integer',
            'year_to_clear' => 'required|integer|same:confirm_year'
        ], [
            'year_to_clear.same' => 'O ano digitado para confirmação está incorreto.'
        ]);

        $year = $request->year_to_clear;

        // Deleta todos os dias letivos onde o ano da data corresponda ao selecionado
        $deletedCount = SchoolDay::whereYear('date', $year)->delete();

        return redirect()->route('admin.calendar.index')
            ->with('success', "Ação concluída! O calendário do ano de {$year} foi completamente apagado ({$deletedCount} dias removidos).");
    }
}
