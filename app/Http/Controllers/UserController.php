<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();
        return response()->json($user);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,

        ]);

        return redirect(route('dashboard', absolute: false));
    }

    public function update(Request $request, $id)
    {
        $data = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',

        ]);

        $data->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('users')
            ->with('success', 'User updated successfully!');
    }

    public function showRoles()
    {
        $data = Roles::all();
        return response()->json($data);
    }

    public function storeRoles(Request $request): RedirectResponse
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $data = Roles::create([
            'role_name' => $request->role_name,
            'description' =>  $request->description,
        ]);

        return redirect()->route('roles')
            ->with('success', 'Expense category created successfully!');
    }

    public function updateRoles(Request $request, $id)
    {
        $data = Roles::findOrFail($id);

        $request->validate([
            'role_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $data->update([
            'role_name' => $request->role_name,
            'description' => $request->description,
        ]);

        return redirect()->route('roles')
            ->with('success', 'Role updated successfully!');
    }

    // public function updateRole(Request $request, $id)
    // {
    //     try {
    //         $role = Roles::findOrFail($id);
    //         $role->role_name = $request->input('role_name');
    //         $role->description = $request->input('description');
    //         $role->save();

    //         return response()->json($role, 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Error updating role', 'error' => $e->getMessage()], 500);
    //     }
    // }

    public function deleteRoles($id)
    {
        try {
            $data = Roles::findOrFail($id);
            $data->delete();
            return response()->json(['message' => 'Role deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting role', 'error' => $e->getMessage()], 500);
        }
    }
}
