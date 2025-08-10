<?php

namespace App\Filament\Resources\ScanResource\Pages;

use App\Filament\Resources\ScanResource;
use App\Jobs\CutMediaIntoFrames;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditScan extends EditRecord
{
    protected static string $resource = ScanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * @return void
     */
    protected function afterSave(): void
    {
        /**
         * Start process
         */
        if (
            CutMediaIntoFrames::dispatch($this->record)
                ->onConnection('redis')
                ->onQueue('media_cutting')
        ) {
            Notification::make()
                ->title('Starting scanning process...')
                ->info()
                ->send();
        }
    }
}
