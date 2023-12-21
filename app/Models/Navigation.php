<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    /**
     * Relationships
     */
    protected $guarded = [];

    protected $casts = [
        'json' => 'array',
        'tags' => 'array',
    ];
    
    public function taxonomy()
    {
        return $this->belongsTo(Taxonomy::class)->where('type', 'Navigation')->orderBy('id');
    }

    public function items()
    {
        return $this->hasMany(Navigation::class, 'navigation_id', 'id');
    }

    public function menus()
    {
        return $this->belongsTo(Navigation::class, 'navigation_id', 'id')->where('type', 'menu');
    }
}
