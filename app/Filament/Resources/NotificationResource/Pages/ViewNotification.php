<?php

namespace App\Filament\Resources\NotificationResource\Pages;

use App\Filament\Resources\NotificationResource;
use App\Models\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Route;

class ViewNotification extends ViewRecord
{
    protected static string $resource = NotificationResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = Notification::find($data['id']);
        $record->read_at = now();
        $record->save();

        return $data;
    }
}
