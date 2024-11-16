<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserCreateFormRequest;
use App\Http\Requests\UserUpdateFormRequest;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Http;


use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::paginate(10);
            return view('users.index', compact('users'));
        } catch (\Exception $e) {

            return redirect()->route('usuarios.index')->with('error', 'Error al cargar los usuarios.');
        }
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.save', compact('roles'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'names' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:8',
                'specialization' => 'required|string|max:255',
                'availability' => 'required|string|in:Disponible,No Disponible',
            ]);

            $doctor = User::create([
                'name' => $validatedData['names'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'specialization' => $validatedData['specialization'],
                'availability' => $validatedData['availability'],
            ]);

            $doctor->assignRole('doctor');

            return redirect()->route('doctores.index')->with('success', 'Doctor creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('doctores.index')->with('error', 'Error al crear el doctor: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return view('users.show', compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado.');
        }
    }

    public function edit(string $id)
    {
        try {
            $roles = Role::all();
            $user = User::findOrFail($id);

            return view('users.save', compact('user', 'roles'));
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Error al cargar el usuario.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(UserUpdateFormRequest $request, string $id)
     {
        try {
            //dd($request->all());

            $user = User::findOrFail($id);

            $user->names = $request->input('names');

            if ($request->input('role')) {
                $user->roles()->sync([$request->input('role')]);
            }
     
            $user->save();
     
            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Error al actualizar el usuario.');
        }
     }

    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Error al eliminar el usuario.');
        }
    }

    public function trashed()
    {
        try {
            $users = User::onlyTrashed()->with('roles')->paginate(10);
            return view('users.trashed', compact('users'));
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Error al cargar los usuarios eliminados.');
        }
    }

    public function restore(string $id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();

            return redirect()->route('usuarios.index')->with('success', 'Usuario restaurado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Error al restaurar el usuario.');
        }
    }
}