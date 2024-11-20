<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuestos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('{{ asset('images/cerdo.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 8px;
            padding: 30px;
            margin-top: 20px;
        }
        h1 {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .btn-primary, .btn-danger, .btn-success {
            border-radius: 20px;
        }
        .table thead {
            background-color: #333;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Sistema de Administración de Presupuestos</h1>

        <!-- Formulario de búsqueda por fechas -->
        <div class="mb-4">
            <form action="{{ route('presupuestos.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ request('fecha_inicio') }}">
                </div>
                <div class="col-md-4">
                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ request('fecha_fin') }}">
                </div>
                <div class="col-md-4 align-self-end">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a href="{{ route('presupuestos.index') }}" class="btn btn-secondary">Limpiar</a>
                </div>
            </form>
        </div>

        <!-- Mensajes de éxito o error -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Botón para agregar presupuesto -->
        <div class="text-center mb-4">
            <a href="{{ route('presupuestos.create') }}" class="btn btn-primary">Agregar Nuevo Presupuesto</a>
        </div>

        <!-- Tabla de presupuestos -->
        <table class="table table-hover table-dark">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Monto Total</th>
                    <th>Abonado</th>
                    <th>Pendiente</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($presupuestos as $presupuesto)
                    <tr>
                        <td>{{ $loop->iteration }}</td> <!-- Muestra el índice consecutivo -->
                        <td>{{ $presupuesto->titulo }}</td>
                        <td>{{ $presupuesto->descripcion }}</td>
                        <td>${{ number_format($presupuesto->monto, 2) }}</td>
                        <td>${{ number_format($presupuesto->abonos, 2) }}</td>
                        <td>${{ number_format($presupuesto->monto - $presupuesto->abonos, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($presupuesto->fecha)->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('presupuestos.edit', $presupuesto->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('presupuestos.destroy', $presupuesto->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar este presupuesto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#abonarModal{{ $presupuesto->id }}">Abonar</button>

                            <!-- Modal de Abonar -->
                            <div class="modal fade" id="abonarModal{{ $presupuesto->id }}" tabindex="-1" aria-labelledby="abonarModalLabel{{ $presupuesto->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Abonar al Presupuesto</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <form action="{{ route('presupuestos.abonar', $presupuesto->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p><strong>Título:</strong> {{ $presupuesto->titulo }}</p>
                                                <p><strong>Monto Total:</strong> ${{ number_format($presupuesto->monto, 2) }}</p>
                                                <p><strong>Abonado:</strong> ${{ number_format($presupuesto->abonos, 2) }}</p>
                                                <p><strong>Pendiente:</strong> ${{ number_format($presupuesto->monto - $presupuesto->abonos, 2) }}</p>
                                                <div class="mb-3">
                                                    <label for="cantidad" class="form-label">Cantidad a Abonar</label>
                                                    <input type="number" name="cantidad" id="cantidad" class="form-control" step="0.01" min="0.01" max="{{ $presupuesto->monto - $presupuesto->abonos }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-success">Registrar Abono</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No hay presupuestos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="text-center mt-4">
            {{ $presupuestos->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
