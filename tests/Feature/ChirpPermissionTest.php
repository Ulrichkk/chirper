<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chirp;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChirpPermissionTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private User $otherUser;
    private Chirp $chirp;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer deux utilisateurs distincts
        $this->owner = User::factory()->create();
        $this->otherUser = User::factory()->create();
        
        // Créer un chirp appartenant au premier utilisateur
        $this->chirp = Chirp::factory()->create([
            'user_id' => $this->owner->id,
            'message' => 'Chirp original'
        ]);
    }

    public function test_user_cannot_update_others_chirp()
    {
        $response = $this->actingAs($this->otherUser)
            ->put("/chirps/{$this->chirp->id}", [
                'message' => 'Tentative de modification non autorisée'
            ]);

        // Vérifier que la requête est interdite
        $response->assertStatus(403);
        
        // Vérifier que le chirp n'a pas été modifié
        $this->assertDatabaseHas('chirps', [
            'id' => $this->chirp->id,
            'message' => 'Chirp original',
            'user_id' => $this->owner->id
        ]);
    }
}