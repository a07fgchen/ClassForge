<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return inertia('rbac/roles/Index', [
            'roles' => Role::all()
        ]);
    }

    //
    public function create(Request $request)
    {
        return inertia('rbac/roles/Create');
    }

    public function show($roleId)
    {
        $role = Role::findOrFail($roleId);
        return inertia('rbac/roles/Show', [
            'role' => $role
        ]);
    }
}
