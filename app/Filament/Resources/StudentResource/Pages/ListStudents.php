<?php

namespace App\Filament\Resources\StudentResource\Pages;

use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'accept' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'accept')),
            'off' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'off')),
            'male' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('gender', 'male')),
            'female' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('gender', 'female')),
        ];
    }
}
