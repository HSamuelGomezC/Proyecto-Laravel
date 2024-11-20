<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Presupuesto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('{{ asset('images/cerdo2.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }

        .container {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 8px;
            padding: 30px;
            margin-top: 20px;
            width: 100%;
            max-width: 600px;
        }

        h1 {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
        }

        .form-control {
            border-radius: 20px;
        }

        .btn-primary {
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agregar Nuevo Presupuesto</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('presupuestos.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control" value="{{ old('titulo') }}" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="monto" class="form-label">Monto</label>
                <input type="number" name="monto" id="monto" class="form-control" step="0.01" value="{{ old('monto') }}" required>
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha') }}" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Guardar</button>
            <a href="{{ route('presupuestos.index') }}" class="btn btn-secondary w-100 mt-3">Cancelar</a>
        </form>
    </div>
</body>
</html>
