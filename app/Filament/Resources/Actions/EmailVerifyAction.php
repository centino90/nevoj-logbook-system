<?php

namespace App\Filament\Resources\Actions;

use Filament\Support\Actions\Concerns\CanCustomizeProcess;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class EmailVerifyAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'email verification';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Verify');

        $this->modalHeading(fn (): string => "Verify " . ucwords($this->getRecordTitle()));

        $this->successNotificationTitle("Verified!");

        $this->color('success');

        $this->icon('heroicon-s-check-circle');

        $this->requiresConfirmation();

        $this->hidden(static function (Model $record): bool {
            if ($record->email_verified_at) {
                return true;
            }

            return false;
        });

        $this->action(function (): void {
            $this->process(static function (Model $record) {
                $record->email_verified_at = now();
                $record->save();
            });

            // $this->failure();

            $this->success();
        });
    }
}
