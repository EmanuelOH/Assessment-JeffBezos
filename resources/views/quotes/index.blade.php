<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Mis Citas</title>
</head>
<body>
<x-navbar></x-navbar>

<div class="container mt-4">
    <h2>Mis Citas</h2>

    @if(session('success'))
        <div class="alert alert-success">
            <strong>Éxito!</strong> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <strong>Error!</strong> {{ session('error') }}
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('citas.create') }}" class="btn btn-primary">Crear Cita</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Doctor</th>
                    <th>Paciente</th>
                    <th>Fecha</th>
                    <th>Razón</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotes as $quote)
                    <tr>
                        <td>{{ $quote->doctor ? $quote->doctor->names : 'No asignado'}}</td>
                        <td>{{ $quote->patient ? $quote->patient->names : 'No asignado' }}</td>
                        <td>{{ \Carbon\Carbon::parse($quote->date)->format('d/m/Y H:i') }}</td>
                        <td>{{ $quote->reason }}</td>
                        <td>{{ ucfirst($quote->status) }}</td>
                        <td>
                            <a href="{{ route('citas.edit', $quote->id) }}" class="btn btn-warning btn-sm">Editar</a>
                            <form action="{{ route('citas.destroy', $quote->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $quotes->links() }}
</div>
</body>
</html>
