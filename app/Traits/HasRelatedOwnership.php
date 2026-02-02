<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasRelatedOwnership
{
    /**
     * Boot the trait to add the Global Scope via a relation.
     * Default relation is 'event'.
     */
    protected static function bootHasRelatedOwnership()
    {
        static::addGlobalScope('ownership', function (Builder $builder) {
            $user = Auth::guard('admin')->user();
            if ($user && $user->role === User::ROLE_ADMIN_EVENT) {
                $relation = (new static)->getOwnershipRelation();
                $builder->whereHas($relation, function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
        });
    }

    /**
     * Get the relation name for ownership check.
     */
    public function getOwnershipRelation(): string
    {
        return property_exists($this, 'ownershipRelation') ? $this->ownershipRelation : 'event';
    }
}
