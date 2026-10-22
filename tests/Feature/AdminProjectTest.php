<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_project_without_media(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->post(route('admin.projects.store'), ['title' => 'New Work', 'slug' => 'new-work', 'summary' => 'A meaningful project summary.', 'status' => 'draft', 'sort_order' => 0])->assertRedirect();
        $this->assertDatabaseHas('projects', ['slug' => 'new-work', 'created_by' => $admin->id]);
    }

    public function test_project_slug_is_unique(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Project::create(['title' => 'One', 'slug' => 'same', 'summary' => 'Summary', 'status' => 'draft']);
        $this->actingAs($admin)->post(route('admin.projects.store'), ['title' => 'Two', 'slug' => 'same', 'summary' => 'Summary', 'status' => 'draft'])->assertSessionHasErrors('slug');
    }
}
