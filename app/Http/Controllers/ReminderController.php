<?php

namespace App\Http\Controllers;

use App\Models\Recordatorio; // Usaremos el modelo Recordatorio
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReminderController extends Controller
{
    /**
     * Muestra el formulario para crear un nuevo recordatorio.
     */
    public function create()
    {
        // La vista del formulario que me mostraste
        return view('reminders.create'); 
    }

    /**
     * Almacena el nuevo recordatorio.
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos del formulario
        $validated = $request->validate([
            // 'title' será el título de la Nota
            'title' => 'required|string|max:255', 
            // 'due_date' será la fecha de vencimiento del Recordatorio
            'due_date' => 'required|date|after_or_equal:now',
        ]);

        // Usamos una transacción para asegurar que la Nota y el Recordatorio se creen juntos
        DB::transaction(function () use ($validated) {
            
            // 2. Crear la Nota (ya que el Recordatorio está relacionado 1:1 con la Nota)
            // Asumimos que el Recordatorio es el contenido principal, así que el contenido de la Nota es vacío/descripción.
            $nota = Auth::user()->notas()->create([
                'titulo' => $validated['title'],
                'contenido' => 'Recordatorio creado a partir del formulario de alerta.', // Contenido de la nota por defecto
            ]);
            
            // 3. Crear el Recordatorio relacionado (hasOne)
            $nota->recordatorio()->create([
                'fecha_vencimiento' => $validated['due_date'],
                'completado' => false, // Siempre se crea como pendiente
            ]);
        });
        
        return redirect()->route('notas.index')->with('success', '¡Recordatorio y Nota creados exitosamente!');
    }

    /**
     * Muestra una lista de recordatorios.
     */
    public function index()
    {
        // Obtener solo las notas con recordatorios activos del usuario logueado.
        // El Global Scope 'activa' en el modelo Nota ya aplica el filtro por fecha/completado.
        $notas = Auth::user()->notas()->get();

        // Esta vista sería 'reminders/index.blade.php'
        return view('reminders.index', compact('notas'));
    }
}