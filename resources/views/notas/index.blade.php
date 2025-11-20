<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nota con Vencimiento</title>
    {{-- Estilos integrados para cumplir con el diseño solicitado --}}
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #f7f7f7; }
        .container { max-width: 800px; margin: auto; background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; margin-bottom: 25px; }
        
        /* Mensajes de error y éxito */
        .error-message { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
            padding: 15px; 
            margin-bottom: 25px; 
            border-radius: 5px; 
            font-weight: bold;
        }

        /* Secciones de Formulario */
        .form-section { 
            border: 1px solid #e0e0e0; 
            padding: 20px; 
            margin-top: 0; 
            border-radius: 6px; 
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        /* Formulario Elementos */
        form > div { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"], input[type="datetime-local"], textarea { 
            width: 100%; 
            padding: 8px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box;
        }
        button[type="submit"] { 
            cursor: pointer; 
            transition: background-color 0.3s;
            background-color: #007bff; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
            font-size: 1em; 
            width: 150px;
        }
        button[type="submit"]:hover { background-color: #0056b3 !important; }

        /* Estilo para mensajes de error de Laravel */
        .laravel-error { color: red; font-size: 0.9em; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Crear Nueva Nota 📝</h1>
        
        {{-- Mostrar errores de validación si existen --}}
        @if ($errors->any())
            <div class="error-message">
                <strong style="color: #721c24;">¡Oops! Hubo un problema con tu solicitud:</strong>
                <ul style="margin-top: 5px; list-style-type: disc; margin-left: 20px; font-weight: normal;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario para Crear Nota --}}
        <div class="form-section">
            <form action="{{ route('notas.store') }}" method="POST">
                @csrf
                
                {{-- Campo Título --}}
                <div>
                    <label for="titulo">Título de la Nota</label>
                    <input type="text" 
                           name="titulo" 
                           id="titulo" 
                           required 
                           value="{{ old('titulo') }}"
                           class="@error('titulo') input-error @enderror">
                    @error('titulo')<div class="laravel-error">{{ $message }}</div>@enderror
                </div>
                
                {{-- Campo Contenido --}}
                <div>
                    <label for="contenido">Contenido / Descripción</label>
                    <textarea name="contenido" 
                              id="contenido" 
                              rows="4" 
                              class="@error('contenido') input-error @enderror">{{ old('contenido') }}</textarea>
                    @error('contenido')<div class="laravel-error">{{ $message }}</div>@enderror
                </div>
                
                {{-- Campo Fecha Vencimiento --}}
                <div>
                    <label for="fecha_vencimiento">Fecha y Hora de Vencimiento (Opcional)</label>
                    <input type="datetime-local" 
                           name="fecha_vencimiento" 
                           id="fecha_vencimiento" 
                           value="{{ old('fecha_vencimiento') }}"
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           class="@error('fecha_vencimiento') input-error @enderror">
                    @error('fecha_vencimiento')<div class="laravel-error">{{ $message }}</div>@enderror
                </div>

                {{-- Botón de Enviar --}}
                <div style="text-align: right; padding-top: 10px;">
                    <button type="submit">
                        Crear Nota
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>