<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chirp;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChirpUpdateTest extends TestCase
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
            'message' => 'Original message'
        ]);
    }

    public function test_un_utilisateur_peut_modifier_son_chirp()
    {
        $response = $this->actingAs($this->user)
            ->put("/chirps/{$this->chirp->id}", [
                'message' => 'chirp modifie'
            ]);

        $response->assertStatus(302);
        $response->assertRedirect();
        
        $this->assertDatabaseHas('chirps', [
            'id' => $this->chirp->id,
            'message' => 'chirp modifie',
            'user_id' => $this->user->id
        ]);
    }
}