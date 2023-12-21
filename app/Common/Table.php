<?php

namespace App\Common;

use Filament\Tables;
use Illuminate\Support\Str;

class Table
{
    public static function PublishStatus()
    {
        return Tables\Columns\TextColumn::make('status')
            ->badge()
            ->color(fn (string $state): string => match ($state) {
                'draft' => 'gray',
                'published' => 'success',
            });
    }
}
