<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('roles')
            ->orderBy('name')
            ->paginate(10);

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role'     => ['required', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($data['role']);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    public function edit(User $usuario)
    {
        $roles = Role::orderBy('name')->get();

        return view('usuarios.edit', [
            'usuario' => $usuario,
            'roles'   => $roles,
        ]);
    }

    public function update(Request $request, User $usuario)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $usuario->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role'     => ['required', 'exists:roles,name'],
        ]);

        $usuario->name  = $data['name'];
        $usuario->email = $data['email'];

        if (!empty($data['password'])) {
            $usuario->password = Hash::make($data['password']);
        }

        $usuario->save();
        $usuario->syncRoles([$data['role']]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $usuario)
    {
        // opcional: impedir excluir a si mesmo
        if (auth()->id() === $usuario->id) {
            return back()->with('error', 'Você não pode excluir o próprio usuário.');
        }

        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }
}