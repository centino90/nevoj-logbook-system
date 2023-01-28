<?php

namespace App\Filament\Resources\Actions\Bulk;

use Filament\Support\Actions\Concerns\CanCustomizeProcess;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class EmailVerifyBulkAction extends BulkAction
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'Bulk Email Verification';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Verify');

        $this->modalHeading(fn (): string => "Verify " . ucwords($this->getPluralModelLabel()));

        $this->successNotificationTitle("Verified!");

        $this->color('success');

        $this->icon('heroicon-s-check-circle');

        $this->requiresConfirmation();

        // $this->hidden(function (HasTable $livewire): bool {
            //TODO: continue after adding a custom filter for email verify
            // $trashedFilterState = $livewire->getTableFilterState(TrashedFilter::class) ?? [];

            // if (! array_key_exists('value', $trashedFilterState)) {
            //     return false;
            // }

            // return blank($trashedFilterState['value']);

            // $trashedFilterState = $livewire->getTableFilterState(TrashedFilter::class) ?? [];

            // if (! array_key_exists('value', $trashedFilterState)) {
            //     return false;
            // }

            // if ($trashedFilterState['value']) {
            //     return false;
            // }

            // return filled($trashedFilterState['value']);
        // });

        $this->action(function (): void {
            $passwords = collect();

            $this->process(static function (Collection $records) use($passwords): void {
                $records->each(function (Model $record) use($passwords): void {
                    $pwd = 'resetted';
                    // $pwd = Str::random(20);
                    $record->password = Hash::make($pwd);
                    $record->save();

                    $passwords->push(['userId' => $record->id, 'password' => $pwd]);
                });
            });

            dd($passwords);
            $this->success();
        });

        dd('yes');

        $this->deselectRecordsAfterCompletion();
    }
}
