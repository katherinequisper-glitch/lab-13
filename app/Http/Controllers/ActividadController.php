<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\Actividad;
use Illuminate\Http\Request;

class ActividadController extends Controller
{
    /**
     * Guarda una nueva actividad para una Nota específica.
     */
    public function store(Request $request, Nota $nota)
    {
        $validated = $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        // Crea la actividad usando la relación
        $nota->actividads()->create([
            'descripcion' => $validated['descripcion'],
        ]);

        return back()->with('success', 'Actividad agregada a la nota.');
    }

    /**
     * Marca una actividad como completada (toggle).
     */
    public function update(Request $request, Nota $nota, Actividad $actividad)
    {
        // Opcional: Verificación de pertenencia
        if ($actividad->nota_id !== $nota->id) {
            abort(404, 'La actividad no pertenece a esta nota.');
        }

        // Cambia el estado 'completada' al opuesto
        $actividad->update([
            'completada' => !$actividad->completada,
        ]);

        return back()->with('success', 'Estado de actividad actualizado.');
    }
}