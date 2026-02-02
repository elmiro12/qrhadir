<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasOwnership;

class Participant extends Model
{
    use HasFactory, HasOwnership;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'user_id',
    ];

    public function eventParticipants()
    {
        return $this->hasMany(EventParticipant::class);
    }
}

