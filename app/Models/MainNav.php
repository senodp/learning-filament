<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MainNav extends Model
{
    protected $table = 'navigations';

    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (MainNav $nav) {
            $nav->user_id = auth()->id();
            $nav->group = 'main';
            $nav->type = 'menu';
            return $nav;
        });

        static::addGlobalScope('main', function (Builder $builder) {
            $builder->where('group', 'main')->where('type', 'menu');
        });
    }

    public function items()
    {
        return $this->hasMany(Navigation::class, 'navigation_id', 'id')->where('type', '<>', 'menu');
    }
}
