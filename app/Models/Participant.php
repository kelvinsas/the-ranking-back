<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Sanctum\HasApiTokens;

class Participant extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'referral_code',
        'referred_by',
        'points',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * JWT - Retorna o ID que será armazenado no token.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * JWT - Retorna um array com claims personalizados (se necessário).
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function referrer()
    {
        return $this->belongsTo(Participant::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(Participant::class, 'referred_by');
    }
}
