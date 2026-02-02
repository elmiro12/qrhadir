<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasRelatedOwnership;

class AttendanceQrToken extends Model
{
    use HasFactory, HasRelatedOwnership;

    protected $ownershipRelation = 'eventParticipant.event';

    protected $fillable = [
        'event_participant_id',
        'token',
        'expired_at',
    ];

    public function getRouteKeyName()
    {
        return 'token';
    }

    public function eventParticipant()
    {
        return $this->belongsTo(EventParticipant::class);
    }
}
