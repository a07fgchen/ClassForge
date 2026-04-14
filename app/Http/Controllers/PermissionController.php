<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('rbac/permissions/Index', [
            'permissions' => Permission::with('module:id,name')->get([
                'id',
                'name',
                'description',
                'slug',
                'module_id',
                'updated_at',
            ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('rbac/permissions/Create', [
            'modules' => Module::get(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'slug' => 'required|string|max:255|unique:permissions,slug',
            'description' => 'nullable|string|max:255',
            'module_id' => 'required|integer|exists:modules,id',
            'assignedRoles' => 'array',
            'assignedRoles.*' => 'string|exists:roles,name',
        ]);

        Permission::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
            'module_id' => $validated['module_id'],
        ]);

        return redirect()->route('rbac.permissions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        return inertia('rbac/permissions/Show', [
            'permission' => Permission::with('module:id,name')->findOrFail($id, [
                'id',
                'name',
                'description',
                'slug',
                'module_id',
                'updated_at',
            ]),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return inertia('rbac/permissions/Edit', [
            'permission' => Permission::findOrFail($id),
            'modules' => Module::query()->get(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions', 'name')->ignore($id)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('permissions', 'slug')->ignore($id)],
            'description' => 'nullable|string|max:255',
            'module_id' => 'required|integer|exists:modules,id',
        ]);

        Permission::findOrFail($id)->update($validated);

        return redirect()->route('rbac.permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        return redirect()->route('rbac.permissions.index');
    }
}
