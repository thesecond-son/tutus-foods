<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Regression: with APP_ENV=production Filament rejects any user model that
 * doesn't implement FilamentUser, which locked staff out of /dashboard.
 */
class FilamentAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.env' => 'production']);
    }

    protected function userWithRole(?string $role): User
    {
        $user = User::factory()->create();
        if ($role) {
            $user->assignRole(Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']));
        }
        return $user;
    }

    public function test_admin_can_open_the_panel_in_production(): void
    {
        $this->actingAs($this->userWithRole('admin'))->get('/dashboard')->assertOk();
    }

    public function test_baker_can_open_the_panel_in_production(): void
    {
        $this->actingAs($this->userWithRole('baker'))->get('/dashboard')->assertOk();
    }

    public function test_user_without_a_staff_role_is_forbidden(): void
    {
        $this->actingAs($this->userWithRole(null))->get('/dashboard')->assertForbidden();
    }

    public function test_guest_is_sent_to_the_login_page(): void
    {
        $this->get('/dashboard')->assertRedirect('/dashboard/login');
    }
}
