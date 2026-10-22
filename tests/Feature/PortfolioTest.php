<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_are_available(): void
    {
        foreach (['/', '/tentang', '/proyek', '/lab', '/layanan', '/kontak', '/privacy'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_local_csp_allows_vite_without_weakening_other_origins(): void
    {
        config(['app.env' => 'local']);

        $csp = $this->get('/')->headers->get('Content-Security-Policy');

        $this->assertStringContainsString('http://[::1]:5173', $csp);
        $this->assertStringContainsString('ws://[::1]:5173', $csp);
        $this->assertStringNotContainsString("'unsafe-eval'", $csp);
    }

    public function test_only_published_projects_are_public(): void
    {
        Project::create(['title' => 'Published', 'slug' => 'published', 'summary' => 'Public', 'status' => 'published']);
        $draft = Project::create(['title' => 'Secret Draft', 'slug' => 'secret', 'summary' => 'Hidden', 'status' => 'draft']);
        $this->get('/proyek')->assertSee('Published')->assertDontSee('Secret Draft');
        $this->get('/proyek/'.$draft->slug)->assertNotFound();
    }

    public function test_guest_cannot_open_admin_dashboard(): void
    {
        $this->get('/'.config('portfolio.admin_path').'/dashboard')->assertRedirect();
    }

    public function test_non_admin_is_forbidden(): void
    {
        $user = User::factory()->create(['role' => 'viewer']);
        $this->actingAs($user)->get('/'.config('portfolio.admin_path').'/dashboard')->assertForbidden();
    }

    public function test_contact_validation_and_storage(): void
    {
        $this->post('/kontak', ['name' => 'A', 'email' => 'bad', 'subject' => 'Halo', 'message' => 'short'])->assertSessionHasErrors();
        $this->post('/kontak', ['name' => 'Fadhillah', 'email' => 'ME@EXAMPLE.COM', 'subject' => 'Kolaborasi', 'message' => 'Saya ingin mendiskusikan proyek digital baru bersama Anda.', 'website' => ''])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('contact_messages', ['email' => 'me@example.com']);
    }
}
