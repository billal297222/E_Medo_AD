<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use App\Models\Role;
// use App\Models\Permission;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RoleController extends Controller
{
    //
    public function index()
    {
       $data['roles'] = Role::where('id', '!=', 1)->get();
        return view('backend.layouts.permission.index',$data);
    }
    public function create()
    {
       $data['permissions'] = Permission::all();
        return view('backend.layouts.permission.create',$data);
    }

   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:roles,name',
        'permissions' => 'required|array', // ensure permissions are selected
        'permissions.*' => 'exists:permissions,name', // optional: check each permission exists
    ]);

    // Create the role
    $role = Role::create(['name'=> $request->name]);

    // Sync permissions
    $role->syncPermissions($request->permissions); // fixed typo

    flash()->success('Role Created Successfully!');
    return redirect()->route('admin.role.list');
}

public function show($id)
{
    $role = Role::with('permissions')->findOrFail($id);
    $permissions = Permission::all(); // so you can list all permissions
    return view('backend.layouts.permission.edit', compact('role', 'permissions'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|unique:roles,name,' . $id,
    ]);

    $role = Role::findOrFail($id);
    $role->update(['name' => $request->name]);

    // Sync permissions
    $role->syncPermissions($request->permissions);

    return redirect()->route('admin.role.list', $id)->with('success', 'Role updated successfully!');
}


}
