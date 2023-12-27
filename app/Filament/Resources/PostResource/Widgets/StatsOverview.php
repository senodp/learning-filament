<?php

namespace App\Filament\Resources\PostResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\Post;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
//namespace App\Filament\Widgets;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Post', Post::all()->count()),
            //->description('32k increase'),
            //->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Publish', Post::where('status', TRUE)->count())
                ->description('Post Publish'),
            Stat::make('Hidden', Post::where('status', FALSE)->count())
                ->description('Post Publish'),
            Stat::make('Student', Student::all()->count()),
            Stat::make('Teacher', Teacher::all()->count()),
            Stat::make('Subject', Subject::all()->count()),
        ];
    }

}
