<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_redirects_to_the_filament_panel(): void
    {
        $this->get(route('dashboard'))->assertRedirect('/admin');
    }

    public function test_guests_are_redirected_to_the_panel_login(): void
    {
        $this->get('/admin')->assertRedirect(route('filament.admin.auth.login'));
    }

    public function test_authenticated_users_can_visit_the_panel_dashboard(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'kaprodi']))
            ->get('/admin')
            ->assertOk();
    }
}
