<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'participant_id',
        'ip_address',
        'user_agent',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
