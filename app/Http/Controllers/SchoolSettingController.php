<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;
use Illuminate\Http\Request;

class SchoolSettingController extends Controller
{
    public function edit()
    {
        $setting = SchoolSetting::firstOrCreate(
            ['id' => 1],
            [
                'current_year' => now()->year,
                'active_bimester' => 1,
            ]
        );

        return view('settings.school', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_year' => ['required', 'integer', 'min:2020'],
            'active_bimester' => ['required', 'integer', 'between:1,4'],
        ]);

        $setting = SchoolSetting::first();

        $setting->update([
            'current_year' => $request->current_year,
            'active_bimester' => $request->active_bimester,
        ]);

        return back()->with('success', 'Configurações atualizadas com sucesso!');
    }
}
