<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserRoleController extends Controller
{
    //
    public function index()
    {
        return inertia('rbac/user-roles/Index', [
            'userRoles' => User::with('roles')->get(),
        ]);
    }
}
