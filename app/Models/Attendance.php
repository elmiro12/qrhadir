<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasRelatedOwnership;

class Attendance extends Model
{
    use HasFactory, HasRelatedOwnership;

    protected $ownershipRelation = 'eventParticipant.event';

    protected $fillable = [
        'event_participant_id',
        'attendance_date',
        'checkin_time',
    ];

    public function eventParticipant()
    {
        return $this->belongsTo(EventParticipant::class);
    }
}

