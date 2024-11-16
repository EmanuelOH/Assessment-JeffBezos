<?php

// app/Http/Controllers/PermissionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // Mostrar todos los permisos
    public function index()
    {
        $permissions = Permission::all();
        return view('permisos.index', compact('permissions'));
    }

    // Mostrar formulario para crear un permiso
    public function create()
    {
        return view('permisos.savePermission');
    }

    // Guardar un nuevo permiso
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        // Crear permiso
        Permission::create(['name' => $request->name]);

        return redirect()->route('permisos.index');
    }

    // Mostrar formulario para editar un permiso
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permisos.savePermission', compact('permission'));
    }

    // Actualizar un permiso
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update(['name' => $request->name]);

        return redirect()->route('permisos.index');
    }

    // Eliminar un permiso
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permisos.index');
    }
}