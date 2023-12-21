<?php

namespace App\Filament\Resources\MainNavResource\Pages;

use App\Filament\Resources\MainNavResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMainNavs extends ManageRecords
{
    protected static string $resource = MainNavResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->slideOver(),
        ];
    }
}
