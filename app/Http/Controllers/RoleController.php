<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
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

        if (Role::where('display_name', $validated['display_name'])->first()) {
            return back()->withErrors(['display_name' => '顯示名稱重複'])->withInput();
        }

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
            'role' => Role::with(['users', 'permissions'])->findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return inertia('rbac/roles/Edit', [
            'role' => Role::with('permissions')->findOrFail($id),
            'permissions' => Permission::with('module:id,name')
                ->get()
                ->groupBy('module.name'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        $validated = $request->validated();

        $role = Role::findOrFail($id);

        $role->update([
            'display_name' => $validated['display_name'],
            'description' => $validated['description'],
            'scope' => $validated['scope'],
            'is_protected' => $validated['is_protected'],
        ]);

        $role->permissions()->sync($validated['permissions']);

        return redirect()->route('rbac.roles.index');
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
