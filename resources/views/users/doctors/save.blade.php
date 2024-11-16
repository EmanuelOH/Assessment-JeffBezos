<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>{{ isset($doctor) ? 'Editar Doctor' : 'Agregar Doctor' }}</title>
</head>
<body>
<div class="container">
    <h1>{{ isset($doctor) ? 'Editar Doctor' : 'Agregar Doctor' }}</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Se encontraron algunos problemas:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($doctor) ? route('doctores.update', $doctor->id) : route('doctores.store') }}" method="POST">
        @csrf
        @if(isset($doctor))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="names" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="names" name="names" value="{{ old('names', $doctor->names ?? '') }}">
        </div>

        @if(!isset($doctor))
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $doctor->email ?? '') }}">
        </div>
        @endif

        @if(!isset($doctor))
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>
        @endif

        <div class="mb-3">
            <label for="specialization" class="form-label">Especialización</label>
            <input type="text" class="form-control" id="specialization" name="specialization" value="{{ old('specialization', $doctor->specialization ?? '') }}">
        </div>

        <div class="mb-3">
            <label for="availability" class="form-label">Disponibilidad</label>
            <select class="form-control" id="availability" name="availability">
                <option value="">Selecciona la disponibilidad</option>
                <option value="Disponible" {{ old('availability', $doctor->availability ?? '') == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                <option value="No Disponible" {{ old('availability', $doctor->availability ?? '') == 'No Disponible' ? 'selected' : '' }}>No Disponible</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($doctor) ? 'Actualizar Doctor' : 'Crear Doctor' }}</button>
        <a href="{{ route('doctores.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
