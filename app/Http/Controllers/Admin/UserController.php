<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $this->authorize('manage users');

        $query = User::with('roles')
            ->withCount(['uploadedItems', 'collections', 'ratings']);

        // Filtros
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%', 'and')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', '=', $request->role, 'and');
            });
        }

        $users = $query->latest()->paginate(20);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'roles' => Role::all(),
            'filters' => $request->only(['search', 'role']),
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $this->authorize('manage users');

        return Inertia::render('Admin/Users/Create', [
            'roles' => Role::all(),
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(\App\Http\Requests\Admin\User\StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Asignar roles
        if (!empty($validated['roles'])) {
            $user->assignRole($validated['roles']);
        } else {
            $user->assignRole('user'); // Rol por defecto
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $this->authorize('view users');

        $user->load([
            'roles',
            'uploadedItems' => function ($query) {
                $query->with(['categories'])->latest()->take(10);
            },
            'collections' => function ($query) {
                $query->withCount('items')->latest()->take(10);
            },
            'ratings' => function ($query) {
                $query->with('item')->latest()->take(10);
            },
        ]);

        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $this->authorize('manage users');

        $user->load('roles');

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(\App\Http\Requests\Admin\User\UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ])->save();

        // Actualizar contraseña si se proporcionó
        if (!empty($validated['password'])) {
            $user->fill([
                'password' => Hash::make($validated['password']),
            ])->save();
        }

        // Sincronizar roles
        if (isset($validated['roles'])) {
            $user->syncRoles($validated['roles']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        $this->authorize('manage users');

        // No permitir eliminar el propio usuario
        if ($user->id === Auth::user()?->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        User::destroy($user->id);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Assign role to user.
     */
    public function assignRole(Request $request, User $user)
    {
        $this->authorize('manage users');

        $validated = $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->assignRole($validated['role']);

        return back()->with('success', 'Rol asignado exitosamente.');
    }

    /**
     * Remove role from user.
     */
    public function removeRole(Request $request, User $user)
    {
        $this->authorize('manage users');

        $validated = $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->removeRole($validated['role']);

        return back()->with('success', 'Rol removido exitosamente.');
    }
}
