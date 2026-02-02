<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasOwnership
{
    /**
     * Boot the trait to add the Global Scope.
     */
    protected static function bootHasOwnership()
    {
        static::addGlobalScope('ownership', function (Builder $builder) {
            $user = Auth::guard('admin')->user();
            if ($user && $user->role === User::ROLE_ADMIN_EVENT) {
                $builder->where($builder->getQuery()->from . '.user_id', $user->id);
            }
        });

        static::creating(function ($model) {
            $user = Auth::guard('admin')->user();
            if ($user && !$model->user_id) {
                $model->user_id = $user->id;
            }
        });
    }

    /**
     * Relationship to the owner (User).
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
