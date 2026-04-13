<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name_user')->get();
        return view('users.show', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_empleado' => 'nullable|string|max:50',
            'usuario'     => 'required|string|max:100|unique:users,usuario',
            'name_user'   => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:users,email',
            'password'    => 'required|string|min:6|confirmed',
            'rol'         => 'required|string|max:50',
        ], [
            'usuario.unique'   => 'Ese nombre de usuario ya está en uso.',
            'email.unique'     => 'Ese correo ya está registrado.',
            'password.min'     => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        User::create([
            'id_empleado' => $request->id_empleado,
            'usuario'     => $request->usuario,
            'name_user'   => $request->name_user,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'rol'         => $request->rol,
            'estatus'     => 1,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'id_empleado' => 'nullable|string|max:50',
            'usuario'     => 'required|string|max:100|unique:users,usuario,' . $user->id,
            'name_user'   => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:users,email,' . $user->id,
            'password'    => 'nullable|string|min:6|confirmed',
            'rol'         => 'required|string|max:50',
        ], [
            'usuario.unique'     => 'Ese nombre de usuario ya está en uso.',
            'email.unique'       => 'Ese correo ya está registrado.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $datos = [
            'id_empleado' => $request->id_empleado,
            'usuario'     => $request->usuario,
            'name_user'   => $request->name_user,
            'email'       => $request->email,
            'rol'         => $request->rol,
        ];

        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        $user->update($datos);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function toggle(User $user)
    {
        $user->update(['estatus' => $user->estatus ? 0 : 1]);

        $msg = $user->estatus ? 'Usuario activado.' : 'Usuario desactivado.';
        return back()->with('success', $msg);
    }
}
