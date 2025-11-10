<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes and Reminders</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: auto; }
        .success-message { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .user-section { border: 1px solid #ccc; padding: 15px; margin-top: 20px; border-radius: 5px; }
        .note { border-left: 3px solid #007bff; padding-left: 10px; margin-top: 10px; }
        .pending { background-color: #ffc107; color: #343a40; padding: 3px 6px; border-radius: 3px; font-size: 0.8em; }
        .completed { background-color: #28a745; color: white; padding: 3px 6px; border-radius: 3px; font-size: 0.8em; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Notes and Reminders</h1>
        
        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <hr>
        <h2>Formulario para Crear Nota</h2>
        <form action="{{ route('notas.store') }}" method="POST">
            @csrf
            
            <div>
                <label for="user_id">Seleccionar Usuario</label>
                <select name="user_id" id="user_id" required>
                    <option value="">-- Seleccionar --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('user_id')<div>{{ $message }}</div>@enderror
            </div>
            
            <br>
            <div>
                <label for="titulo">Título Nota</label>
                <input type="text" name="titulo" id="titulo" required value="{{ old('titulo') }}">
                @error('titulo')<div>{{ $message }}</div>@enderror
            </div>
            
            <br>
            <div>
                <label for="contenido">Contenido</label>
                <textarea name="contenido" id="contenido" required>{{ old('contenido') }}</textarea>
                @error('contenido')<div>{{ $message }}</div>@enderror
            </div>
            
            <br>
            <div>
                <label for="fecha_vencimiento">Fecha Vencimiento</label>
                <input type="datetime-local" name="fecha_vencimiento" id="fecha_vencimiento" required value="{{ old('fecha_vencimiento') }}">
                @error('fecha_vencimiento')<div>{{ $message }}</div>@enderror
            </div>

            <br>
            <button type="submit" style="background-color: blue; color: white; padding: 10px 15px; border: none; border-radius: 5px;">Añadir Nota</button>
        </form>
        <hr>

        <h2>Listado de Usuarios y Notas</h2>
        @foreach ($users as $user)
            <div class="user-section">
                <h3>Usuario: {{ $user->name }} ({{ $user->total_notas }} Active Notes)</h3>
                
                @forelse ($user->notas as $nota)
                    <div class="note">
                        <h4>{{ $nota->titulo_formateado }}</h4> 
                        <p>{{ $nota->contenido }}</p>
                        
                        @if ($nota->recordatorio)
                            @php
                                $estado = $nota->recordatorio->completado ? 'Completed' : 'Pending';
                                $clase = $nota->recordatorio->completado ? 'completed' : 'pending';
                            @endphp
                            <p>
                                **Due:** {{ \Carbon\Carbon::parse($nota->recordatorio->fecha_vencimiento)->format('Y-m-d H:i') }} 
                                <span class="{{ $clase }}">{{ $estado }}</span>
                            </p>
                        @endif
                    </div>
                @empty
                    <p>No tiene notas activas.</p>
                @endforelse
            </div>
        @endforeach
    </div>
</body>
</html>