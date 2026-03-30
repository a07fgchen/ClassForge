<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $roles = Role::all();

        return inertia('rbac/roles/Index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $permissions = Permission::with('module:id,name')
            ->get()
            ->groupBy('module.name');

        return inertia('rbac/roles/Create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'display_name' => 'required|string|max:255|ascii',
            'description' => 'sometimes|nullable|string|max:255',
            'scope' => 'required|in:1,2',
            'is_protected' => 'sometimes|boolean',
            'permissions' => 'required|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role = Role::create([
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'scope' => $validated['scope'],
            'slug' => Str::slug($validated['display_name']),
            'is_protected' => $validated['is_protected'] ?? 0,
        ]);
        $role->permissions()->sync($validated['permissions']);
        return redirect()->route('rbac.roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return inertia('rbac/roles/Show', [
            'id' => $id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return inertia('rbac/roles/Edit', [
            'id' => $id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        return redirect()->route('rbac.roles.index');
    }
}
