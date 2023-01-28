<?php

namespace App\Filament\Resources\Actions\Bulk;

use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Filament\Support\Actions\Concerns\CanCustomizeProcess;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class ResetPasswordBulkAction extends BulkAction
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'Bulk Reset Password';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Password Reset Selected');

        $this->modalHeading(fn (): string => "Password Reset " . ucwords($this->getPluralModelLabel()));

        $this->successNotificationTitle("Password Resetted!");

        $this->color('primary');

        $this->icon('heroicon-s-lock-open');

        $this->requiresConfirmation();

        $this->action(function (): void {
            $passwords = collect();

            $this->process(static function (Collection $records) use($passwords): void {
                $records->each(function (Model $record) use($passwords): void {
                    $pwd = Str::random(10);
                    $record->password = Hash::make($pwd);
                    $record->save();

                    $passwords->push(['userId' => $record->id, 'password' => $pwd]);
                });
            });

            $recipient = auth()->user();
            $recipient->notify(
                Notification::make()
                    ->title($passwords->count() . ' users were password resetted')
                    ->body($passwords->toJson())
                    ->toDatabase(),
            );

            $this->success();
        });

        $this->deselectRecordsAfterCompletion();
    }
}
