<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chirp;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;

class ChirpUpdateValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Chirp $chirp;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->chirp = Chirp::factory()->create([
            'user_id' => $this->user->id,
            'message' => 'Message original'
        ]);
    }

    public function test_chirp_ne_peut_pas_etre_vide()
    {
        $response = $this->actingAs($this->user)
            ->put("/chirps/{$this->chirp->id}", [
                'message' => ''
            ]);

        $response->assertSessionHasErrors('message');
        
        // Vérifier que le message original n'a pas été modifié
        $this->assertDatabaseHas('chirps', [
            'id' => $this->chirp->id,
            'message' => 'Message original'
        ]);
    }
}