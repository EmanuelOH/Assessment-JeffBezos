<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Eliminados</title>
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Usuarios Archivados</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>ROLES</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->names }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->isNotEmpty() ? $user->roles->first()->name : 'No asignado' }}</td>
                    <td>
                        <form action="{{ route('usuarios.restore', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay usuarios archivados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
</div>
</body>
</html>