<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Chirp;
use App\Models\User;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test; // Importer les attributs PHPUnit

class ChirpFiltrageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    #[Test] public function it_displays_only_chirps_created_in_the_last_7_days()
    {
        // Créer des "chirps" avec différentes dates
        Chirp::factory()->create(['created_at' => Carbon::now()->subDays(2)]); // Récent
        Chirp::factory()->create(['created_at' => Carbon::now()->subDays(8)]); // Trop ancien
        Chirp::factory()->create(['created_at' => Carbon::now()]); // Aujourd'hui

        // Appeler la route qui retourne les "chirps" récents
        $response = $this->getJson('/api/chirps/recent');

        // Assurer que le statut de la réponse est 200
        //$response->assertStatus(200);

        // Vérifier que seuls les "chirps" des 7 derniers jours sont retournés
        $response->assertJsonCount(2); // Deux "chirps" récents

        $response->assertJsonFragment([
            'created_at' => Carbon::now()->subDays(2)->toISOString(),
        ]);

        $response->assertJsonFragment([
            'created_at' => Carbon::now()->toISOString(),
        ]);

        $response->assertJsonMissing([
            'created_at' => Carbon::now()->subDays(8)->toISOString(),
        ]);
    }
}
