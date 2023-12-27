<?php

namespace App\Models;

class Page extends BaseModel
{
    protected static function booted(): void
    {
        static::creating(function (Page $page) {
            $page->user_id = auth()->id();
            return $page;
        });
    }

    /**
     * Relationships
     */
    // public function taxonomy()
    // {
    //     return $this->belongsTo(Taxonomy::class, 'taxonomy_id', 'id');
    // }
}
