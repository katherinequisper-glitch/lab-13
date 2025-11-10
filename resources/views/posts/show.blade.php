@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Muestra la publicación --}}
    <h1 class="mb-3">{{ $post->title }}</h1>
    <div class="card p-4 mb-4 shadow-sm">
        <p class="lead">{{ $post->content }}</p>
        <p class="text-muted"><small>Por: **{{ $post->user->name }}** | Publicado el: {{ $post->created_at->format('d M Y H:i') }}</small></p>
    </div>
    
    <a href="{{ route('posts.index') }}" class="btn btn-secondary mb-5">← Volver al Listado</a>
    
    <hr>

    {{-- ====================================================== --}}
    {{-- 1. FORMULARIO PARA AGREGAR NUEVO COMENTARIO (Sección 4.1) --}}
    {{-- ====================================================== --}}
    @auth
        <div class="comment-form mt-4 mb-5 p-4 border rounded bg-light">
            <h4>Deja tu comentario</h4>
            <form method="POST" action="{{ route('comments.store', $post) }}">
                @csrf
                <div class="mb-3">
                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3" placeholder="Escribe tu comentario aquí..." required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Publicar Comentario</button>
            </form>
        </div>
    @else
        <div class="alert alert-info text-center mt-4" role="alert">
            Debes <a href="{{ route('login') }}">iniciar sesión</a> para poder dejar un comentario.
        </div>
    @endauth

    {{-- ====================================================================================== --}}
    {{-- 2. LISTA DE COMENTARIOS CON BOTONES DE EDICIÓN Y ELIMINACIÓN (Sección 4.2) --}}
    {{-- ====================================================================================== --}}
    <div class="comments-list mt-5">
        <h3>Comentarios ({{ $post->comments->count() }})</h3>
        
        @forelse ($post->comments->sortByDesc('created_at') as $comment)
            <div class="card mb-3 shadow-sm border-left-primary">
                <div class="card-body">
                    
                    {{-- Contenido del Comentario --}}
                    <p class="card-text">{{ $comment->content }}</p>
                    
                    <footer class="blockquote-footer mt-2">
                        Escrito por **{{ $comment->user->name }}** | {{ $comment->created_at->diffForHumans() }}
                    </footer>

                    {{-- Botones condicionales: Solo visibles si el usuario tiene permiso (Gate) --}}
                    @can('update-comment', $comment)
                        <div class="mt-3">
                            {{-- Botón de Editar que muestra el formulario JS --}}
                            <button class="btn btn-sm btn-info me-2 text-white" onclick="showEditForm({{ $comment->id }})">
                                Editar
                            </button>

                            {{-- Formulario de Eliminación --}}
                            <form method="POST" action="{{ route('comments.destroy', $comment) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este comentario? Esta acción es irreversible.');">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    @endcan

                    {{-- Formulario de Edición Oculto --}}
                    <div id="edit-form-{{ $comment->id }}" class="mt-3 p-3 bg-light border rounded" style="display:none;">
                        <h5>Editar Comentario</h5>
                        <form method="POST" action="{{ route('comments.update', $comment) }}">
                            @csrf
                            @method('PUT')
                            <textarea name="content" class="form-control mb-2" rows="2" required>{{ $comment->content }}</textarea>
                            <button type="submit" class="btn btn-success btn-sm me-2">Guardar Cambios</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="hideEditForm({{ $comment->id }})">Cancelar</button>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <p class="text-center text-muted">Aún no hay comentarios. ¡Sé el primero en comentar! ✍️</p>
        @endforelse
    </div>

</div>

{{-- Script JavaScript para la funcionalidad de mostrar/ocultar el formulario de edición --}}
<script>
    function showEditForm(commentId) {
        document.getElementById('edit-form-' + commentId).style.display = 'block';
    }
    
    function hideEditForm(commentId) {
        document.getElementById('edit-form-' + commentId).style.display = 'none';
    }
</script>
@endsection