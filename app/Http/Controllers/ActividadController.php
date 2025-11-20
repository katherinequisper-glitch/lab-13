<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\Actividad;
use Illuminate\Http\Request;

class ActividadController extends Controller
{
    // Almacenar nueva actividad
    public function store(Request $request, Nota $nota)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $nota->actividads()->create([
            'descripcion' => $request->descripcion,
            'completada' => false,
        ]);

        return redirect()->route('notas.index')->with('success', 'Actividad agregada!');
    }

    // Actualizar actividad (marcar como completada/incompleta)
    public function update(Request $request, Nota $nota, Actividad $actividad)
    {
        // Verificar que la actividad pertenece a la nota
        if ($actividad->nota_id !== $nota->id) {
            abort(403);
        }

        $actividad->update([
            'completada' => !$actividad->completada
        ]);

        return redirect()->route('notas.index')->with('success', 'Actividad actualizada!');
    }
}