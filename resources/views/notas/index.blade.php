<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratorio N° 14 - Notas y Actividades</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .user-card { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 8px; }
        .note-item { margin-top: 10px; padding: 8px; border-left: 3px solid #007bff; background-color: #f8f9fa; }
        .note-actions { margin-left: 15px; }
        .form-delete { display: inline; }
        .note-list { margin-left: 20px; }
    </style>
</head>
<body>

    <h1>📝 Listado de Usuarios y Notas Activas (Eloquent Avanzado)</h1>

    @if (session('success'))
        <div style="color: green; border: 1px solid green; padding: 10px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    @foreach ($users as $user)
        <div class="user-card">
            <h2>{{ $user->name }} ({{ $user->email }})</h2>
            
            {{-- Muestra el resultado de la subconsulta del Lab 13 --}}
            <p><strong>Total Notas Activas:</strong> {{ $user->total_notas_activas }}</p>

            <h3>Notas Activas:</h3>
            
            {{-- La colección $user->notas solo contiene las notas activas gracias al Global Scope --}}
            @if ($user->notas->isEmpty())
                <p>No hay notas activas para este usuario.</p>
            @else
                <ul class="note-list">
                    @foreach ($user->notas as $nota)
                        <li class="note-item">
                            {{-- Usa el Accesor para el título formateado --}}
                            <strong>{{ $nota->titulo_formateado }}</strong>
                            <p>Contenido: {{ Str::limit($nota->contenido, 50) }}</p>

                            {{-- Muestra el recordatorio asociado --}}
                            <p>Vencimiento: 
                                {{ $nota->recordatorio ? $nota->recordatorio->fecha_vencimiento->format('d/m/Y H:i') : 'N/A' }}
                            </p>
                            
                            {{-- Implementación de la eliminación (Requisito Lab 14) --}}
                            <div class="note-actions">
                                <a href="#" style="margin-right: 10px;">Ver/Editar Actividades</a> 
                                
                                <form action="{{ route('notas.destroy', $nota) }}" method="POST" class="form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Estás seguro de eliminar la nota, recordatorio y actividades?')" style="color: red; border: none; background: none; cursor: pointer;">
                                        [Eliminar Nota]
                                    </button>
                                </form>
                            </div>
                            
                            {{-- Opcional: Lista básica de Actividades (si Actividad.php existe y está relacionado) --}}
                            @if ($nota->actividads->isNotEmpty())
                                <ul>
                                    @foreach ($nota->actividads as $actividad)
                                        <li>- {{ $actividad->descripcion }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endforeach

    <hr>
    
    <h2>➕ Crear Nueva Nota con Recordatorio</h2>
    <form action="{{ route('notas.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 10px;">
            <label>Usuario Propietario:</label>
            {{-- Se asume que el modelo User está disponible globalmente, o se importó arriba --}}
            <select name="user_id" required>
                <option value="">Seleccione un usuario</option>
                @foreach (\App\Models\User::all() as $userOption)
                    <option value="{{ $userOption->id }}">{{ $userOption->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="margin-bottom: 10px;">
            <label>Título:</label>
            <input type="text" name="titulo" required value="{{ old('titulo') }}">
        </div>
        <div style="margin-bottom: 10px;">
            <label>Contenido:</label>
            <textarea name="contenido" rows="3" required>{{ old('contenido') }}</textarea>
        </div>
        <div style="margin-bottom: 10px;">
            <label>Fecha y Hora de Vencimiento:</label>
            <input type="datetime-local" name="fecha_vencimiento" required value="{{ old('fecha_vencimiento') }}">
        </div>
        <button type="submit">Crear Nota y Recordatorio</button>
        
        @if ($errors->any())
            <div style="color: red; margin-top: 10px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>

</body>
</html>