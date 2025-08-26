<?php

namespace App\Http\Controllers;

use App\Models\User;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('roles.index', compact('users'));
    }
}
