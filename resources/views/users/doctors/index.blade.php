<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Lista de Doctores</title>
</head>
<body>
<x-navbar></x-navbar>
<div class="container">
    <h2 class="mt-4">Lista de Doctores</h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            <strong>Bien hecho!</strong> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <strong>Error!</strong> {{ session('error') }}
        </div>
    @endif

    @if(Auth::user()->roles->first()->name == 'admin')
        <div class="mb-3">
            <a href="{{ route('doctores.create') }}" class="btn btn-success">Agregar Doctor</a>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>NOMBRE</th>
                <th>CORREO</th>
                <th>DISPONIBILIDAD</th>
                <th>ESPECIALIZACIÓN</th>
                <th>ACCIONES</th>
            </tr>
            </thead>
            <tbody>
            @foreach($doctors as $doctor)
               <tr>
                   <td>{{ $doctor->id }}</td>
                   <td>{{ $doctor->names }}</td>
                   <td>{{ $doctor->email }}</td>
                   <td>{{ $doctor->availability }}</td>
                   <td>{{ $doctor->specialization }}</td>
                   <td>
                       <form action="{{ route('doctores.destroy', $doctor->id) }}" method="POST">
                           @method('DELETE')
                           @csrf
                           <a href="{{ route('doctores.edit', $doctor->id) }}" class="btn btn-sm btn-warning">Editar</a>
                           <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                       </form>
                   </td>
               </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    {{ $doctors->links() }}
</div>
</body>
</html>
