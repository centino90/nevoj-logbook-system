<?php

namespace App\Filament\Resources\Actions;

use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Filament\Support\Actions\Concerns\CanCustomizeProcess;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'reset password';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Password Reset');

        $this->modalHeading(fn (): string => "Password Reset " . ucwords($this->getRecordTitle()));

        $this->successNotificationTitle("Password Resetted!");

        $this->color('primary');

        $this->icon('heroicon-s-lock-open');

        $this->requiresConfirmation();

        $this->action(function (): void {
            $this->process(static function (Model $record) {
                $pwd = Str::random(10);
                $record->password = Hash::make($pwd);
                $record->save();

                $recipient = auth()->user();
                $recipient->notify(
                    Notification::make()
                        ->title($record->name . '\'s password was resetted')
                        ->body(collect(['userId' => $record->id, 'password' => $pwd])->toJson())
                        // ->actions([
                        //     NotificationAction::make('view')
                        //     ->button()
                        //     ->url(route('filament.resources.notifications.view', $record), shouldOpenInNewTab: true)
                        // ])
                        ->toDatabase(),
                );
            });

            $this->success();
        });
    }
}
