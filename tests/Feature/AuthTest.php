<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $role = Role::create([
            'name' => 'Admin',
            'description' => 'Administrator',
        ]);

        $permission = Permission::create(['name' => 'Kelola User']);
        $role->permissions()->attach($permission->id);

        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Super Admin',
            'username' => 'admin',
            'email' => 'admin@school.test',
            'password' => bcrypt('Password123!'),
            'status' => 'active',
        ]);

        $response = $this->post('/login/admin', [
            'login' => $user->email,
            'password' => 'Password123!',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        $dashboard = $this->get('/dashboard');
        $dashboard->assertOk();
    }

    public function test_admin_can_create_category_and_tag(): void
    {
        $role = Role::create([
            'name' => 'Admin',
            'description' => 'Administrator',
        ]);

        $permUser = Permission::create(['name' => 'Kelola User']);
        $permWeb  = Permission::create(['name' => 'Kelola Website']);
        $role->permissions()->attach([$permUser->id, $permWeb->id]);

        $user = User::create([
            'role_id' => $role->id,
            'name' => 'Super Admin',
            'username' => 'admin2',
            'email' => 'admin2@school.test',
            'password' => bcrypt('Password123!'),
            'status' => 'active',
        ]);

        $this->actingAs($user);

        $this->post('/admin/categories', ['nama' => 'Berita', 'slug' => 'berita'])->assertRedirect('/admin/categories');
        $this->post('/admin/tags', ['nama' => 'Sekolah'])->assertRedirect('/admin/tags');
    }
}
