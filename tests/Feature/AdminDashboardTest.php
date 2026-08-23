<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_unauthenticated_user_redirected_to_login()
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_login_page()
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }

    public function test_admin_can_login_with_valid_credentials()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();
    }

    public function test_authenticated_admin_can_access_dashboard()
    {
        $admin = User::where('email', 'admin@example.com')->first();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200)
            ->assertSee('Dashboard Overview');
    }

    public function test_authenticated_admin_can_access_company_profile_edit()
    {
        $admin = User::where('email', 'admin@example.com')->first();

        $response = $this->actingAs($admin)->get('/admin/company-profile');

        $response->assertStatus(200)
            ->assertSee('Nusantara Digital Solusindo');
    }

    public function test_authenticated_admin_can_access_services_index()
    {
        $admin = User::where('email', 'admin@example.com')->first();

        $response = $this->actingAs($admin)->get('/admin/services');

        $response->assertStatus(200)
            ->assertSee('Custom Web Application Development');
    }

    public function test_authenticated_admin_can_access_articles_index()
    {
        $admin = User::where('email', 'admin@example.com')->first();

        $response = $this->actingAs($admin)->get('/admin/articles');

        $response->assertStatus(200)
            ->assertSee('Tren Cloud Computing & AI di Tahun 2026');
    }
}
