<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    /**
     * List all staff accounts (admins + employees).
     */
    public function index()
    {
        $employees = User::orderByRaw("FIELD(role, 'admin', 'employee')")
            ->orderBy('name')
            ->paginate(20);

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'role'     => ['required', Rule::in(['admin', 'employee'])],
            'password' => ['required', 'confirmed', Password::min(8)],
            'is_active' => 'nullable|boolean',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = $validated['password']; // hashed by cast
        $user->role = $validated['role'];
        $user->is_admin = $validated['role'] === 'admin'; // keep legacy flag in sync
        $user->is_active = $request->boolean('is_active', true);
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('admin.employees.index')
            ->with('success', ucfirst($validated['role']) . ' account for ' . $user->name . ' created.');
    }

    public function edit(User $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($employee->id)],
            'role'     => ['required', Rule::in(['admin', 'employee'])],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'is_active' => 'nullable|boolean',
        ]);

        // Safety: an admin cannot demote or deactivate their own account
        // (prevents accidental self-lockout).
        $editingSelf = $employee->id === $request->user()->id;
        $role = $editingSelf ? 'admin' : $validated['role'];
        $isActive = $editingSelf ? true : $request->boolean('is_active', true);

        $employee->name = $validated['name'];
        $employee->email = $validated['email'];
        $employee->role = $role;
        $employee->is_admin = $role === 'admin';
        $employee->is_active = $isActive;
        if (! empty($validated['password'])) {
            $employee->password = $validated['password'];
        }
        $employee->save();

        return redirect()->route('admin.employees.index')
            ->with('success', $employee->name . "'s account updated.");
    }

    public function destroy(Request $request, User $employee)
    {
        if ($employee->id === $request->user()->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($employee->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'You cannot delete the last administrator.');
        }

        $name = $employee->name;
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', $name . "'s account deleted.");
    }
}
