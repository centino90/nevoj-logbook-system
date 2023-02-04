<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // protected function mutateFormDataBeforeFill(array $data): array
    // {
    //    dd($data);
    // }

    // protected function beforeSave(): void
    // {
    //     dd($this->record);
    // }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if(!$data['served_at']) {
            $data['served_at'] = null;
        } else {
            $data['served_at'] = now()->toDateTimeString();
        }
        // $data['served_at'] = now()->toDateTimeString();
        // dd($data);
        // $data['last_edited_by_id'] = auth()->id();

        return $data;
    }



    protected function afterFill(): void
    {
        foreach (auth()->user()->unreadNotifications as $key => $notification) {
            $notificationSubjectId = json_decode($notification->data['body'], true)['id'];
            if($notificationSubjectId && $notificationSubjectId === $this->record->id) {
                $notification->markAsRead();
            }
        }
    }
}
