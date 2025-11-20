<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Necesario para filtrar las notas del usuario

class NotaController extends Controller
{
    /**
     * Muestra una lista de todas las notas del usuario autenticado.
     * Esta función maneja la ruta GET /notas
     */
    public function index()
    {
        // Obtener solo las notas del usuario autenticado y ordenarlas por fecha de creación (más reciente primero).
        $notas = Auth::user()->notas()->latest()->get(); 
        
        // Asume que tienes una vista en resources/views/notas/index.blade.php
        return view('notas.index', compact('notas'));
    }

    /**
     * Muestra el formulario para crear una nueva nota.
     * Esta función maneja la ruta GET /notas/create
     */
    public function create()
    {
        // Simplemente retorna la vista del formulario
        return view('notas.create');
    }

    /**
     * Almacena una nueva nota, incluyendo una fecha de vencimiento opcional.
     * Esta función maneja la ruta POST /notas
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'nullable|string',
            // La fecha es opcional, pero si existe, debe ser una fecha y posterior o igual a 'now'
            'fecha_vencimiento' => 'nullable|date|after_or_equal:now', 
        ]);
        
        // Crea la nota asociada al usuario actual, incluyendo la fecha de vencimiento
        Auth::user()->notas()->create([
            'titulo' => $validated['titulo'],
            'contenido' => $validated['contenido'],
            'fecha_vencimiento' => $validated['fecha_vencimiento'], // Se guarda el nuevo campo
        ]);
        
        return redirect()->route('notas.index')->with('success', 'Nota creada exitosamente.');
    }
    
    /**
     * Elimina una nota.
     * Esta función maneja la ruta DELETE /notas/{nota} (con borrado en cascada configurado en el modelo).
     */
    public function destroy(Nota $nota)
    {
        // Asegurarse de que el usuario es dueño de la nota
        if (Auth::id() !== $nota->user_id) {
            abort(403, 'No tienes permiso para eliminar esta nota.');
        }

        // El borrado en cascada para actividades se ejecuta automáticamente en el modelo Nota.php
        $nota->delete();

        // Mensaje de éxito limpio
        return back()->with('success', 'Nota y actividades asociadas eliminadas.');
    }
}