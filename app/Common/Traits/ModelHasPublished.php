<?php

namespace App\Common\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ModelHasPublished
{
    /**
     * Local Scopes
     */
    public function scopePublished(Builder $query):void
    {
        $query->where('status', 'published')
            ->whereDate('published_at', '<=', date('Y:m:d H:i:s'))
            ->latest('published_at');
    }
}
