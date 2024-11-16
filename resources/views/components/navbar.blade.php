<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard') }}">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <!-- Ruta para Admin y Doctor -->
                @role('admin|doctor')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctores.index') }}">Doctores</a>
                    </li>
                @endrole

                <!-- Ruta para Admin -->
                @role('admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('usuarios.index') }}">Usuarios</a>
                    </li>
                @endrole

                <!-- Ruta para Admin, Doctor y Paciente -->
                @role('admin|doctor|paciente')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('citas.index') }}">Citas</a>
                    </li>
                @endrole
            </ul>
        </div>
    </div>
</nav>
