<?php

namespace App\Http\Controllers;

use App\Models\User; // Necesario para la subconsulta en el index
use App\Models\Nota;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

class NotaController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios con sus notas activas.
     * Implementa la SUB CONSULTA y carga de relaciones (REQUISITO LAB 13).
     */
    public function index()
    {
        // Cargar usuarios con sus notas activas (gracias al Global Scope en Nota.php)
        // y con sus recordatorios.
        $users = User::with(['notas', 'notas.recordatorio'])
            ->addSelect([
                // Subconsulta para calcular el total de notas activas por usuario
                // Nota: La subconsulta debe replicar la lógica del Global Scope para el conteo.
                'total_notas_activas' => Nota::selectRaw('count(*)')
                    ->whereColumn('user_id', 'users.id')
                    ->whereHas('recordatorio', fn($query) => $query->where('fecha_vencimiento', '>=', now()))
            ])
            ->get();

        // Asume que tienes una vista en resources/views/notas/index.blade.php
        return view('notas.index', compact('users'));
    }

    /**
     * Almacena una nueva nota, creando el Recordatorio asociado (REQUISITO LAB 13).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', 
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'fecha_vencimiento' => 'required|date|after:now', // La fecha es obligatoria y debe ser futura
        ]);
        
        // 1. Crear la Nota (solo con datos de la tabla 'notas')
        $note = Nota::create([
            'user_id' => $validated['user_id'],
            'titulo' => $validated['titulo'],
            'contenido' => $validated['contenido'],
        ]);

        // 2. Crear el Recordatorio usando la relación hasOne (datos de la tabla 'recordatorios')
        $note->recordatorio()->create([
            'fecha_vencimiento' => $validated['fecha_vencimiento'],
        ]);
        
        return redirect()->route('notas.index')->with('success', 'Nota y Recordatorio creados exitosamente.');
    }
    
    /**
     * Elimina una nota (REQUISITO LAB 14).
     * El borrado en cascada para Recordatorio y Actividades se maneja en Nota.php.
     */
    public function destroy(Nota $nota)
    {
        // La llamada a delete() activa el evento 'deleting' en Nota.php,
        // que a su vez elimina el Recordatorio y las Actividades.
        $nota->delete();

        return back()->with('success', 'Nota, recordatorio y actividades eliminadas.');
    }
}