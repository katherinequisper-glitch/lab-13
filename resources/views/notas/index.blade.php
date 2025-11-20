<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Notas</title>

    <style>
        body {
            font-family: Arial;
            background: #eef1f5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 900px;
            margin: auto;
            margin-top: 40px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            color: #444;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 15px;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056d2;
        }

        .note-box {
            padding: 15px;
            background: #fdfdfd;
            border-left: 6px solid #007bff;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        .note-title {
            font-size: 18px;
            font-weight: bold;
        }

        .btn-danger {
            background: #e63946;
            color: white;
            margin-top: 10px;
        }

        .btn-danger:hover {
            background: #c7001f;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Gestión de Notas</h1>

    @if (session('success'))
        <div class="card" style="border-left: 6px solid #2ecc71;">
            <strong style="color:#2ecc71;">✔ {{ session('success') }}</strong>
        </div>
    @endif

    <!-- CREAR NOTA -->
    <div class="card">
        <h2 style="margin-top:0;">Crear Nota</h2>

        <form action="{{ route('notas.store') }}" method="POST">
            @csrf

            <label>Seleccionar Usuario</label>
            <select name="user_id" required>
                <option value="">-- Seleccionar --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>

            <label>Título</label>
            <input type="text" name="titulo" required>

            <label>Contenido</label>
            <textarea name="contenido" rows="3" required></textarea>

            <br><br>
            <button class="btn btn-primary" type="submit">Crear Nota</button>
        </form>
    </div>

    <!-- NOTAS REGISTRADAS -->
    <div class="card">
        <h2 style="margin-top:0;">Notas Registradas</h2>

        @foreach ($users as $user)
            <h3 style="color:#007bff;">👤 {{ $user->name }}</h3>

            @if ($user->notas->isEmpty())
                <p style="color:#888;">No tiene notas registradas.</p>
            @else
                @foreach ($user->notas as $nota)
                    <div class="note-box">
                        <div class="note-title">{{ $nota->titulo }}</div>
                        <p>{{ $nota->contenido }}</p>

                        <form action="{{ route('notas.destroy', $nota) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">Eliminar</button>
                        </form>
                    </div>
                @endforeach
            @endif

            <hr style="border:none; border-top:1px solid #ddd; margin:20px 0;">
        @endforeach

    </div>

</div>

</body>
</html>
