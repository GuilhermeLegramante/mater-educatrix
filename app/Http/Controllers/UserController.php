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

    /**
     * Prepara a tela/formulário para edição de um usuário existente.
     */
    public function edit(User $user)
    {
        $users = User::with(['classrooms', 'subjects'])->get();
        $classrooms = Classroom::all();
        $subjects = Subject::all();

        // Carrega os relacionamentos do usuário selecionado
        $user->load(['classrooms', 'subjects']);

        // Retorna a view index passando o usuário a ser editado
        return view('users.index', compact('users', 'classrooms', 'subjects', 'user'));
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

    /**
     * Remove um usuário do banco de dados e limpa seus vínculos.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // 1. Trava de Segurança: Impede que o usuário logado exclua a si mesmo
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'Você não pode excluir o seu próprio usuário logado!');
        }

        // 2. Remove o usuário do banco de dados
        // Obs: As tabelas pivô (classroom_user e subject_user) serão limpas automaticamente via 'cascade'.
        $user->delete();

        // 3. Redireciona de volta para a lista com mensagem de sucesso
        return redirect()->route('users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }
}
