<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuoteController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            if ($user->hasRole('paciente')) {
                $quotes = $user->quotesAsPatient()->paginate(10);
            } else if ($user->hasRole('doctor')) {
                $quotes = $user->quotesAsDoctor()->paginate(10);
            } else if ($user->hasRole('admin')) {
                $quotes = Quote::paginate(10);
            }

            return view('quotes.index', compact('quotes'));
            
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Error al cargar las citas.');
        }
    }



    public function create()
    {
        try {
            $user = Auth::user();

            if (!$user->hasRole('paciente')) {
                return redirect()->route('citas.index')->with('error', 'No eres un paciente para crear las citas.');
            }

            $doctors = User::role('doctor')->get();
            return view('quotes.save', compact('doctors'));
        } catch (\Exception $e) {
            return redirect()->route('citas.index')->with('error', 'Error al cargar los doctores.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'doctor_id' => 'required|exists:users,id',
                'date' => 'required|date',
                'reason' => 'required|string',
                'status' => 'required|string',
                'nota' => 'nullable|string',
            ]);

            $user = Auth::user();
            
            if (!$user->hasRole('paciente')) {
                return redirect()->route('citas.index')->with('error', 'No tienes permisos para crear citas.');
            }

            $existingQuote = Quote::where('doctor_id', $request->doctor_id)
                                ->whereDate('date', Carbon::parse($request->date)->toDateString())
                                ->first();

            if ($existingQuote) {
                return redirect()->route('citas.save')->with('error', 'El doctor ya está ocupado en esa fecha.');
            }

            Quote::create([
                'patient_id' => $user->id,
                'doctor_id' => $request->doctor_id,
                'date' => $request->date,
                'reason' => $request->reason,
                'status' => $request->status,
                'nota' => $request->nota,
            ]);

            return redirect()->route('citas.index')->with('success', 'Cita creada con éxito.');
        } catch (\Exception $e) {
            return redirect()->route('citas.index')->with('error', 'Error al crear la cita.');
        }
    }

    public function edit(string $id)
    {
        try {
            $quote = Quote::findOrFail($id);
            $user = Auth::user();

            if (!$user->hasRole('paciente')) {
                return redirect()->route('citas.index')->with('error', 'No eres un paciente para crear las citas.');
            }

            $doctors = User::role('doctor')->get();

            return view('quotes.save', compact('quote', 'doctors'));

        } catch (\Exception $e) {
            return redirect()->route('citas.index')->with('error', 'Error al cargar los doctores.');
        }
    }


    public function update(Request $request, string $id)
    {
        try {
            $quote = Quote::findOrFail($id);

            $request->validate([
                'doctor_id' => 'required|exists:users,id',
                'date' => 'required|date',
                'reason' => 'required|string',
                'status' => 'required|string',
                'nota' => 'nullable|string',
            ]);

            $user = Auth::user();
            
            if (!$user->hasRole('paciente')) {
                return redirect()->route('citas.index')->with('error', 'No tienes permisos para actualizar citas.');
            }

            $existingQuote = Quote::where('doctor_id', $request->doctor_id)
                ->whereDate('date', Carbon::parse($request->date)->toDateString())
                ->where('id', '!=', $quote->id)
                ->first();

            if ($existingQuote) {
                return redirect()->route('citas.index')->with('error', 'El doctor ya está ocupado en esa fecha.');
            }

            $quote->update([
                'patient_id' => $user->id,
                'doctor_id' => $request->doctor_id,
                'date' => $request->date,
                'reason' => $request->reason,
                'status' => $request->status,
                'nota' => $request->nota,
            ]);

            return redirect()->route('citas.index')->with('success', 'Cita actualizada con éxito.');
        } catch (\Exception $e) {
            return redirect()->route('citas.index')->with('error', 'Error al actualizar la cita.');
        }
    }


    public function destroy(string $id)
    {
        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('paciente')) {
            return redirect()->route('citas.index')->with('error', 'No tienes permisos para eliminar esta cita.');
        }

        try {
            $quote = Quote::find($id);

            if (!$quote) {
                return redirect()->route('citas.index')->with('error', 'La cita no existe.');
            }

            $quote->delete();
            return redirect()->route('citas.index')->with('success', 'Cita eliminada con éxito.');
        } catch (\Exception $e) {
            return redirect()->route('citas.index')->with('error', 'Error al eliminar la cita.');
        }
    }

}
