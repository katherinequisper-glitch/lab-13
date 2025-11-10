<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; // Para la autorización

class CommentController extends Controller
{
    // Crear/Almacenar un nuevo comentario
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|max:500',
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Comentario publicado.');
    }

    // Actualizar un comentario
    public function update(Request $request, Comment $comment)
    {
        // **Autorización:** Verifica que el usuario autenticado sea el dueño del comentario
        if (Gate::denies('update-comment', $comment)) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|max:500',
        ]);

        $comment->update($validated);

        return back()->with('success', 'Comentario actualizado.');
    }

    // Eliminar un comentario
    public function destroy(Comment $comment)
    {
        // **Autorización:** Verifica que el usuario autenticado sea el dueño del comentario
        if (Gate::denies('delete-comment', $comment)) {
            abort(403);
        }
        
        $comment->delete();

        return back()->with('success', 'Comentario eliminado.');
    }
}
?>
