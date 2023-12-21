<?php

namespace App\Common;

use Filament\Notifications\Notification;

class Notify
{
    public static function success()
    {
        return Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }
}
