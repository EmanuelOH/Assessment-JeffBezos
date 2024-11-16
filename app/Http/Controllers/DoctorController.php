<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        try {
            // Obtiene solo usuarios con rol "doctor"
            $doctors = User::role('doctor')->paginate(10);
            return view('users.doctors.index', compact('doctors'));
        } catch (\Exception $e) {
            return redirect()->route('doctores.index')->with('error', 'Error al cargar los doctores.');
        }
    }

    public function create()
    {
        return view('users.doctors.save');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->all();

            $rules = [
                'names' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed|min:8',
                'specialization' => 'required|string|max:255',
                'availability' => 'required|string|in:Disponible,No Disponible',
            ];

            $validator = \Validator::make($validatedData, $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $doctor = User::create([
                'names' => $validatedData['names'],
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


    public function edit(string $id)
    {
        try {
            $doctor = User::findOrFail($id);
            return view('users.doctors.save', compact('doctor'));
        } catch (\Exception $e) {
            return redirect()->route('doctores.index')->with('error', 'Doctor no encontrado.');
        }
    }

    public function update(Request $request, $id)
    {
        $doctor = User::find($id);

        if (!$doctor) {
            return redirect()->route('doctores.index')->with('error', 'Doctor no encontrado.');
        }

        $validatedData = $request->validate([
            'names' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'availability' => 'required|string',
            'password' => 'nullable|confirmed|min:8',
        ]);

        $doctor->names = $validatedData['names'];
        $doctor->specialization = $validatedData['specialization'];
        $doctor->availability = $validatedData['availability'];

        $doctor->save();

        return redirect()->route('doctores.index')->with('success', 'Doctor actualizado correctamente.');
    }


    public function destroy(string $id)
    {
        try {
            $doctor = User::findOrFail($id);
            $doctor->delete();

            return redirect()->route('doctores.index')->with('success', 'Doctor eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('doctores.index')->with('error', 'Error al eliminar el doctor.');
        }
    }
}
