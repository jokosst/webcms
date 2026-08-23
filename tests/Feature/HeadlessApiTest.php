<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeadlessApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_fetch_company_profile_api()
    {
        $response = $this->getJson('/api/v1/company-profile');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.company_name', 'Nusantara Digital Solusindo');
    }

    public function test_can_fetch_hero_banners_api()
    {
        $response = $this->getJson('/api/v1/hero-banners');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(2, 'data');
    }

    public function test_can_fetch_services_api()
    {
        $response = $this->getJson('/api/v1/services');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(2, 'data');
    }

    public function test_can_fetch_portfolios_api()
    {
        $response = $this->getJson('/api/v1/portfolios');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data.data');
    }

    public function test_can_fetch_articles_api()
    {
        $response = $this->getJson('/api/v1/articles');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(1, 'data.data');
    }

    public function test_can_submit_contact_form_api()
    {
        $response = $this->postJson('/api/v1/contact', [
            'name' => 'Budi Client',
            'email' => 'budi@client.com',
            'phone' => '081299990000',
            'subject' => 'Tanya Layanan Web',
            'message' => 'Halo, saya tertarik dengan layanan pembuatan aplikasi web.',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('inbox_leads', [
            'email' => 'budi@client.com',
            'subject' => 'Tanya Layanan Web',
        ]);
    }
}
