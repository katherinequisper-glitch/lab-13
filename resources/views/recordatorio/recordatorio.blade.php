@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Establecer Nuevo Recordatorio ⏰</h1>

    <form action="{{ route('reminders.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label fw-bold">Asunto del Recordatorio</label>
            <input type="text"
                   name="title"
                   id="title"
                   class="form-control"
                   placeholder="Ej: Reunión con el equipo de Git"
                   required>
            @error('title')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="due_date" class="form-label fw-bold">Fecha y Hora para Recordar</label>
            <input type="datetime-local"
                   name="due_date"
                   id="due_date"
                   class="form-control"
                   required>
            @error('due_date')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-danger btn-lg">
            Crear Recordatorio
        </button>

        <a href="{{ route('reminders.index') }}" class="btn btn-secondary btn-lg ms-2">
            Ver Recordatorios
        </a>
    </form>
</div>
@endsection