<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Events\ChirpCreated;

class Chirp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'likes',
    ];
    protected $dispatchesEvents = [
        'created' => ChirpCreated::class,
    ];
 

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'chirp_user_likes');
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likedByUsers()->where('user_id', $user->id)->exists();
    }
}
