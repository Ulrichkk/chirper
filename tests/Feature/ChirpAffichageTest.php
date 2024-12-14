<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chirp;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChirpAffichaTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_homepage_displays_all_chirps()
    {
        // Arrange: Create multiple chirps with different users
        $chirps = [
            'First test chirp',
            'Second test chirp',
            'Third test chirp'
        ];

        foreach ($chirps as $message) {
            Chirp::factory()->create([
                'message' => $message,
                'user_id' => User::factory()->create()->id
            ]);
        }

        // Act: Make a GET request to the homepage
        $response = $this->get('/');

        // Assert: Verify the response contains all chirps
        $response->assertStatus(200);
        
        foreach ($chirps as $message) {
            $response->assertSee($message);
        }
    }
}   