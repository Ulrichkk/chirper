<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Chirp;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChirpMaximumTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Teste qu'un utilisateur avec 10 chirps ne peut pas en créer un 11ᵉ.
     */
    public function test_user_cannot_create_more_than_10_chirps()
    {
        // Création d'un utilisateur
        $user = User::factory()->create();

        // Authentification de l'utilisateur
        $this->actingAs($user);

        // Création de 10 chirps pour cet utilisateur
        Chirp::factory()->count(10)->create(['user_id' => $user->id]);

        // Tenter de créer un 11ᵉ chirp
        $response = $this->post('/chirps', [
            'content' => 'Ce chirp dépasse la limite autorisée',
        ]);

        // Vérifie que la réponse retourne un statut 403 (interdiction)
        //$response->assertStatus(403);

        // Vérifie que le 11ᵉ chirp n'a pas été créé
        $this->assertEquals(10, Chirp::where('user_id', $user->id)->count());
    }
}
