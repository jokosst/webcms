<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('Nusantara Digital Solusindo');
    }

    public function test_public_user_can_submit_contact_form(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Budi Public',
            'email' => 'budi@public.com',
            'message' => 'Halo, saya ingin bertanya seputar layanan.',
        ]);

        $response->assertRedirect('/#contact');

        $this->assertDatabaseHas('inbox_leads', [
            'email' => 'budi@public.com',
        ]);
    }
}
