<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chirp;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChirpTest extends TestCase
{
    use RefreshDatabase;

    public function  test_un_utilisateur_peut_creer_un_chirp()
    {
        //  Simuler un utilisateur connecté
        $user = User::factory()->create();
        
        // Envoyer une requête POST pour créer un chirp
        $response = $this->actingAs($user)
            ->post('/chirps', [
                'message' => 'Mon premier chirp!'
            ]);

        // Assert: Verify response and database
        $response->assertStatus(302); // Redirect after successful creation
        $response->assertRedirect(); // Verify it redirects somewhere

        // Vérifier que le chirp a été ajouté à la base de donnée
        $this->assertDatabaseHas('chirps', [
            'message' => 'Mon premier chirp!',
            'user_id' => $user->id
        ]);
    }

}    