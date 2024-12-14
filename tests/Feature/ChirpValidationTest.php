<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;

class ChirpValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_un_chirp_ne_peut_pas_avoir_un_contenu_vide()
    {
        $response = $this->actingAs($this->user)
            ->post('/chirps', [
                'message' => ''
            ]);

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseCount('chirps', 0);
    }

    public function test_un_chirp_ne_peut_pas_depasse_255_caracteres()
    {
        $longMessage = Str::random(256); // Generate a string that's too long

        $response = $this->actingAs($this->user)
            ->post('/chirps', [
                'message' => $longMessage
            ]);

        $response->assertSessionHasErrors('message');
        $this->assertDatabaseCount('chirps', 0);
    }

    public function test_chirp_avec_longueur_valid()
    {
        $validMessage = Str::random(255); // Maximum allowed length

        $response = $this->actingAs($this->user)
            ->post('/chirps', [
                'message' => $validMessage
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('chirps', [
            'message' => $validMessage,
            'user_id' => $this->user->id
        ]);
    }
}