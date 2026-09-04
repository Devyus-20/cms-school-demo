<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (auth()->user()?->role?->name === 'Siswa') {
            return redirect()->route('siswa.dashboard');
        }

        return view('dashboard', [
            'users' => User::count(),
            'roles' => Role::count(),
            'permissions' => Permission::count(),
            'posts' => \App\Models\Post::count(),
            'pending_ppdb' => \App\Models\PpdbRegistration::where('status', 'pending')->count(),
            'latest_users' => User::with('role')->latest('id_user')->take(5)->get(),
        ]);
    }

    public function users()
    {
        $users = User::with('role')->latest('id_user')->get();

        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'role_id' => ['nullable', 'exists:roles,id_role'],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $newUser = User::create($data);

        ActivityLog::record('create', "Menambahkan user baru: {$newUser->name} ({$newUser->username})", $newUser);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser(User $user)
    {
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user)
    {
        $data = $request->validate([
            'role_id' => ['nullable', 'exists:roles,id_role'],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id_user . ',id_user'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id_user . ',id_user'],
            'password' => ['nullable', 'min:6'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        ActivityLog::record('update', "Memperbarui data user: {$user->name} ({$user->username})", $user);

        return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser(User $user)
    {
        $userName = $user->name;
        $userUsername = $user->username;
        $user->delete();

        ActivityLog::record('delete', "Menghapus user: {$userName} ({$userUsername})");

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }

    public function roles()
    {
        $roles = Role::with('permissions')->latest('id_role')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function createRole()
    {
        $permissions = Permission::all();

        return view('admin.roles.create', compact('permissions'));
    }

    public function storeRole(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id_permission'],
        ]);

        $role = Role::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        if (!empty($data['permissions'])) {
            $role->permissions()->sync($data['permissions']);
        }

        ActivityLog::record('create', "Menambahkan role baru: {$role->name}", $role);

        return redirect()->route('admin.roles')->with('success', 'Role berhasil ditambahkan.');
    }

    public function editRole(Role $role)
    {
        $permissions = Permission::all();

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function updateRole(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id_role . ',id_role'],
            'description' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id_permission'],
        ]);

        $role->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $role->permissions()->sync($data['permissions'] ?? []);

        ActivityLog::record('update', "Memperbarui role: {$role->name}", $role);

        return redirect()->route('admin.roles')->with('success', 'Role berhasil diperbarui.');
    }

    public function deleteRole(Role $role)
    {
        $roleName = $role->name;
        $role->delete();

        ActivityLog::record('delete', "Menghapus role: {$roleName}");

        return redirect()->route('admin.roles')->with('success', 'Role berhasil dihapus.');
    }

    public function permissions()
    {
        $permissions = Permission::latest('id_permission')->get();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function createPermission()
    {
        return redirect()->route('admin.permissions')->with('info', 'Permission bersifat bawaan sistem (fixed) dan terhubung langsung dengan middleware keamanan Laravel. Silakan buat/edit Role di menu Manajemen Role untuk mengelompokkan izin ini.');
    }

    public function storePermission(Request $request)
    {
        return redirect()->route('admin.permissions')->with('info', 'Permission bersifat bawaan sistem (fixed) dan tidak dapat ditambah secara manual.');
    }

    public function editPermission(Permission $permission)
    {
        return redirect()->route('admin.permissions')->with('info', 'Permission bersifat bawaan sistem (fixed) dan dihubungkan secara langsung ke middleware keamanan Laravel.');
    }

    public function updatePermission(Request $request, Permission $permission)
    {
        return redirect()->route('admin.permissions')->with('info', 'Permission bersifat bawaan sistem (fixed) dan tidak dapat diubah secara manual.');
    }

    public function deletePermission(Permission $permission)
    {
        return redirect()->route('admin.permissions')->with('info', 'Permission bersifat bawaan sistem (fixed) dan tidak dapat dihapus.');
    }
}
