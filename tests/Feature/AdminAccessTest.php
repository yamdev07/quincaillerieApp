<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_access_users_index()
    {
        // Crée un utilisateur admin
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

    $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function non_admin_cannot_access_users_index()
    {
        // Crée un utilisateur caissier
        $user = User::factory()->create([
            'role' => 'caissier',
        ]);

        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertStatus(403);
    }
}
