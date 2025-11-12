<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes and Reminders</title>
    {{-- Los estilos se han incluido aquí para un ejemplo autocontenido, puedes migrarlos a un archivo CSS externo si usas Vite o webpack. --}}
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #f7f7f7; }
        .container { max-width: 800px; margin: auto; background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; margin-bottom: 25px; }
        h2 { border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        
        /* Mensajes */
        .success-message { 
            background: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
            padding: 15px; 
            margin-bottom: 25px; 
            border-radius: 5px; 
            font-weight: bold;
        }

        /* Secciones de Usuario y Notas */
        .form-section, .user-section { 
            border: 1px solid #e0e0e0; 
            padding: 20px; 
            margin-top: 25px; 
            border-radius: 6px; 
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .form-section { margin-top: 0; }
        .user-section h3 { margin-top: 0; color: #007bff; }
        
        /* Formulario */
        form > div { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="datetime-local"], select, textarea { 
            width: 100%; 
            padding: 8px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box;
        }
        button[type="submit"] { 
            cursor: pointer; 
            transition: background-color 0.3s;
        }
        button[type="submit"]:hover { background-color: #0056b3 !important; }

        /* Notas Individuales (Recordatorios) */
        .note { 
            border-left: 5px solid #ffc107; 
            padding: 10px; 
            margin-top: 15px; 
            background-color: #fff9e6;
            border-radius: 4px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
        }
        .note h4 { margin-top: 0; margin-bottom: 5px; color: #333; }
        .note p { margin: 0 0 5px 0; font-size: 0.95em; color: #555; }
        
        /* Estados del Recordatorio */
        .pending { 
            background-color: #ffc107; 
            color: #343a40; 
            padding: 3px 8px; 
            border-radius: 12px; 
            font-size: 0.8em; 
            font-weight: bold;
        }
        .completed { 
            background-color: #28a745; 
            color: white; 
            padding: 3px 8px; 
            border-radius: 12px; 
            font-size: 0.8em; 
            font-weight: bold;
            border-color: #28a745;
        }
        .completed-note { border-left-color: #28a745; background-color: #e6ffe6; }
        .pending-note { border-left-color: #ffc107; background-color: #fff9e6; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Notes and Reminders</h1>
        
        {{-- 1. Mensaje de éxito --}}
        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        {{-- 2. Formulario para Crear Nota --}}
        <div class="form-section">
            <h2>Formulario para Crear Nota</h2>
            <form action="{{ route('notas.store') }}" method="POST">
                @csrf
                
                {{-- Campo Usuario --}}
                <div>
                    <label for="user_id">Seleccionar Usuario</label>
                    <select name="user_id" id="user_id" required>
                        <option value="">-- Seleccionar --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')<div style="color: red; font-size: 0.9em;">{{ $message }}</div>@enderror
                </div>
                
                {{-- Campo Título --}}
                <div>
                    <label for="titulo">Título Nota</label>
                    <input type="text" name="titulo" id="titulo" required value="{{ old('titulo') }}">
                    @error('titulo')<div style="color: red; font-size: 0.9em;">{{ $message }}</div>@enderror
                </div>
                
                {{-- Campo Contenido --}}
                <div>
                    <label for="contenido">Contenido</label>
                    <textarea name="contenido" id="contenido" rows="3" required>{{ old('contenido') }}</textarea>
                    @error('contenido')<div style="color: red; font-size: 0.9em;">{{ $message }}</div>@enderror
                </div>
                
                {{-- Campo Fecha Vencimiento --}}
                <div>
                    <label for="fecha_vencimiento">Fecha Vencimiento</label>
                    <input type="datetime-local" name="fecha_vencimiento" id="fecha_vencimiento" required value="{{ old('fecha_vencimiento') }}">
                    @error('fecha_vencimiento')<div style="color: red; font-size: 0.9em;">{{ $message }}</div>@enderror
                </div>

                {{-- Botón de Enviar --}}
                <div style="text-align: center; padding-top: 10px;">
                    <button type="submit" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 1em; width: 150px;">
                        Añadir Nota
                    </button>
                </div>
            </form>
        </div>
        
        {{-- 3. Listado de Usuarios y Notas --}}
        @foreach ($users as $user)
            <div class="user-section">
                {{-- Título de Usuario con contador de notas activas --}}
                <h3>
                    Usuario: {{ $user->name }} 
                    <span style="font-size: 0.8em; font-weight: normal; color: #555;">({{ $user->total_notas }} Active Notes)</span>
                </h3>
                
                @forelse ($user->notas as $nota)
                    {{-- Usamos la clase condicional para el color de borde según el estado --}}
                    @php
                        $isCompleted = $nota->recordatorio && $nota->recordatorio->completado;
                        $claseNota = $isCompleted ? 'completed-note' : 'pending-note';
                    @endphp
                    
                    <div class="note {{ $claseNota }}">
                        {{-- Título formateado (Accesor) --}}
                        <h4>{{ $nota->titulo_formateado }}</h4> 
                        
                        {{-- Contenido --}}
                        <p>{{ $nota->contenido }}</p>
                        
                        {{-- Recordatorio y Estado --}}
                        @if ($nota->recordatorio)
                            @php
                                $estado = $isCompleted ? 'Completed' : 'Pending';
                                $claseEstado = $isCompleted ? 'completed' : 'pending';
                            @endphp
                            <p style="margin-top: 8px;">
                                <span style="font-weight: bold; color: #333;">Due:</span> 
                                {{ \Carbon\Carbon::parse($nota->recordatorio->fecha_vencimiento)->format('Y-m-d H:i') }} 
                                <span class="{{ $claseEstado }}">{{ $estado }}</span>
                            </p>
                        @endif
                    </div>
                @empty
                    <p style="color: #999;">No tiene notas activas.</p>
                @endforelse
            </div>
        @endforeach
    </div>
</body>
</html>