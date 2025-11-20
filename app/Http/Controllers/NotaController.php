<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\User;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    public function index()
    {
        $users = User::with('notas')->get();
        return view('notas.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'titulo' => 'required',
            'contenido' => 'required',
        ]);

        Nota::create([
            'user_id' => $request->user_id,
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
        ]);

        return redirect()->route('notas.index')->with('success', 'Nota creada correctamente');
    }

    public function destroy(Nota $nota)
    {
        $nota->delete();
        return redirect()->route('notas.index')->with('success', 'Nota eliminada correctamente');
    }
}
