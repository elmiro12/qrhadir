<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasRelatedOwnership;

class ParticipantType extends Model
{
    use HasFactory, HasRelatedOwnership;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'certificate_text',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function eventParticipants()
    {
        return $this->hasMany(EventParticipant::class);
    }
}
