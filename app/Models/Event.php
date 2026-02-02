<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasOwnership;

class Event extends Model
{
    use HasFactory, HasOwnership;

    protected $fillable = [
        'name',
        'slug',
        'start_date',
        'end_date',
        'location',
        'status',
        'user_id',
        'logo',
        'has_certificate',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'user_id'    => 'integer',
        'has_certificate' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = \Illuminate\Support\Str::slug($event->name);
            }
        });

        static::updating(function ($event) {
            if ($event->isDirty('name')) {
                $event->slug = \Illuminate\Support\Str::slug($event->name);
            }
        });
    }

    public function eventParticipants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function participantTypes()
    {
        return $this->hasMany(ParticipantType::class);
    }

    public function idCardTemplate()
    {
        return $this->hasOne(IdCardTemplate::class);
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('assets/images/event-logo/' . $this->logo);
        }
        return null;
    }

    public function template()
    {
        return $this->hasOne(EventTemplate::class);
    }

    public function signatures()
    {
        return $this->hasMany(Signature::class)->orderBy('sort_order');
    }

    public function certificateReports()
    {
        return $this->hasManyThrough(
            CertificateReport::class,
            EventParticipant::class,
            'event_id', // Foreign key on event_participants table...
            'event_participant_id', // Foreign key on certificate_reports table...
            'id', // Local key on events table...
            'id' // Local key on event_participants table...
        );
    }
}

