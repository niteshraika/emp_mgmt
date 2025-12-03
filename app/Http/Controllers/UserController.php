<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        if (!auth()->user()->canManageUsers()) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to view users.');
        }
        $users = User::paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        if (!auth()->user()->canCreateUsers()) {
            return redirect()->route('users.index')->with('error', 'You do not have permission to create users.');
        }
        return view('users.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->canCreateUsers()) {
            return redirect()->route('users.index')->with('error', 'You do not have permission to create users.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'manager', 'viewer'])],
        ]);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }
}
