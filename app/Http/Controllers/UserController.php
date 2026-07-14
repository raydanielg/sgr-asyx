<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:owner']);
    }

    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:owner,finance,admin_manager,supervisor',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($data['role'] === 'owner') {
            $existingOwner = User::where('role', 'owner')->where('status', 'active')->where('id', '!=', auth()->id())->exists();
            if ($existingOwner) {
                return back()->withInput()->with('error', 'A Company Owner account already exists.');
            }
        }

        $user = User::create($data);
        AuditLog::log('create', 'User', $user->id, "Created user: {$user->name} ({$user->role})");

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:owner,finance,admin_manager,supervisor',
            'status' => 'required|in:active,inactive',
        ]);

        if ($data['role'] === 'owner' && $user->role !== 'owner') {
            $existingOwner = User::where('role', 'owner')->where('status', 'active')->where('id', '!=', $user->id)->exists();
            if ($existingOwner) {
                return back()->withInput()->with('error', 'A Company Owner account already exists.');
            }
        }

        $user->update($data);
        AuditLog::log('update', 'User', $user->id, "Updated user: {$user->name}");

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate(['password' => 'required|string|min:8|confirmed']);
        $user->update(['password' => Hash::make($request->password)]);
        AuditLog::log('reset_password', 'User', $user->id, "Reset password for: {$user->name}");

        return back()->with('success', 'Password reset successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);
        AuditLog::log('toggle_status', 'User', $user->id, "Toggled status to {$newStatus} for: {$user->name}");

        return back()->with('success', "User {$newStatus}.");
    }
}
