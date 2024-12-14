<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chirp;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChirpDeleteTest extends TestCase
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
            'message' => 'Chirp à supprimer'
        ]);
    }

    public function test_un_utilisateur_peut_supprimer_son_chirp()
    {
        $response = $this->actingAs($this->user)
            ->delete("/chirps/{$this->chirp->id}");

        $response->assertStatus(302);
        $response->assertRedirect();
        
        $this->assertDatabaseMissing('chirps', [
            'id' => $this->chirp->id
        ]);
    }
}