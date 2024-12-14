<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chirp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChirpLikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_a_chirp()
    {
        $user = User::factory()->create();
        $chirp = Chirp::factory()->create();

        $this->actingAs($user)
            ->post(route('chirps.like', $chirp))
            ->assertStatus(200)
            ->assertJson(['message' => 'Chirp liked successfully!', 'likes' => 1]);

        $this->assertDatabaseHas('chirp_user_likes', [
            'chirp_id' => $chirp->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_cannot_like_a_chirp_twice()
    {
        $user = User::factory()->create();
        $chirp = Chirp::factory()->create();

        $this->actingAs($user)
            ->post(route('chirps.like', $chirp))
            ->assertStatus(200)
            ->assertJson(['message' => 'Chirp liked successfully!', 'likes' => 1]);

        // Deuxième tentative de like
        $this->actingAs($user)
            ->post(route('chirps.like', $chirp))
            ->assertStatus(403)
            ->assertJson(['message' => 'You have already liked this chirp.']);

        $this->assertDatabaseCount('chirp_user_likes', 1);
    }
}
