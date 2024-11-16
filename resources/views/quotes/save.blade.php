<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>{{ isset($quote) ? 'Editar Cita' : 'Crear Cita' }}</title>
</head>
<body>
<div class="container mt-4">
    <h2>{{ isset($quote) ? 'Editar Cita' : 'Crear Cita' }}</h2>

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

    <form action="{{ isset($quote) ? route('citas.update', $quote->id) : route('citas.store') }}" method="POST">
        @csrf
        @if(isset($quote))
            @method('PUT')
        @endif
        
        <div class="mb-3">
            <label for="doctor_id" class="form-label">Doctor</label>
            <select name="doctor_id" id="doctor_id" class="form-select @error('doctor_id') is-invalid @enderror">
                <option value="">Selecciona un doctor</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ (isset($quote) && $quote->doctor_id == $doctor->id) ? 'selected' : '' }}>
                        {{ $doctor->names }}
                    </option>
                @endforeach
            </select>
            @error('doctor_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Fecha</label>
            <input type="datetime-local" name="date" id="date" value="{{ isset($quote) ? \Carbon\Carbon::parse($quote->date)->format('Y-m-d\TH:i') : old('date') }}" class="form-control @error('date') is-invalid @enderror">
            @error('date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="reason" class="form-label">Razón</label>
            <input type="text" name="reason" id="reason" value="{{ isset($quote) ? $quote->reason : old('reason') }}" class="form-control @error('reason') is-invalid @enderror">
            @error('reason')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Estado</label>
            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                <option value="pendiente" {{ (isset($quote) && $quote->status == 'pendiente') ? 'selected' : '' }}>Pendiente</option>
                <option value="confirmado" {{ (isset($quote) && $quote->status == 'confirmado') ? 'selected' : '' }}>Confirmado</option>
                <option value="cancelado" {{ (isset($quote) && $quote->status == 'cancelado') ? 'selected' : '' }}>Cancelado</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nota" class="form-label">Nota</label>
            <textarea name="nota" id="nota" class="form-control @error('nota') is-invalid @enderror">{{ isset($quote) ? $quote->nota : old('nota') }}</textarea>
            @error('nota')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">{{ isset($quote) ? 'Actualizar Cita' : 'Crear Cita' }}</button>
        <a href="{{ route('citas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
