<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['classrooms', 'subjects'])->get();
        $classrooms = Classroom::all();
        $subjects = Subject::all();

        return view('users.index', compact('users', 'classrooms', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users',
            'role'        => 'required',
            'password'    => 'required|min:8|confirmed',
            'classrooms'  => 'nullable|array',
            'classrooms.*' => 'exists:classrooms,id',
            'subjects'    => 'nullable|array',
            'subjects.*'   => 'exists:subjects,id',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'password' => bcrypt($validated['password']),
        ]);

        // Vincula as turmas e disciplinas selecionadas
        if (isset($validated['classrooms'])) {
            $user->classrooms()->sync($validated['classrooms']);
        }

        if (isset($validated['subjects'])) {
            $user->subjects()->sync($validated['subjects']);
        }

        return redirect()->route('users.index')->with('success', 'Usuário salvo com sucesso!');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'role'        => 'required',
            'password'    => 'nullable|min:8',
            'classrooms'  => 'nullable|array',
            'classrooms.*' => 'exists:classrooms,id',
            'subjects'    => 'nullable|array',
            'subjects.*'   => 'exists:subjects,id',
        ]);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'role'  => $validated['role'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => bcrypt($validated['password'])]);
        }

        // Atualiza os vínculos (remove os não selecionados e adiciona os novos)
        $user->classrooms()->sync($request->input('classrooms', []));
        $user->subjects()->sync($request->input('subjects', []));

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }
}
