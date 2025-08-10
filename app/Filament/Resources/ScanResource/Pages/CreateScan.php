<?php

namespace App\Filament\Resources\ScanResource\Pages;

use App\Constants\Queues;
use App\Filament\Resources\ScanResource;
use App\Jobs\CutMediaIntoFrames;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateScan extends CreateRecord
{
    protected static string $resource = ScanResource::class;

    /**
     * @return void
     */
    protected function afterCreate(): void
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
