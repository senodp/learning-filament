<?php

namespace App\Filament\Resources\PostResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Post;

//namespace App\Filament\Widgets;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Post', Post::all()->count()),
            //->description('32k increase'),
            //->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Publish', Post::where('status', TRUE)->count()),
            Stat::make('Hidden', Post::where('status', FALSE)->count()),
        ];
    }

}
