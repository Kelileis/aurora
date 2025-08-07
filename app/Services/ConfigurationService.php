<?php

namespace App\Services;

use Filament\Forms\Components\Toggle;

class ConfigurationService
{
    /**
     * @return array
     */
    public static function constructForm(): array
    {
        return collect(config('app.anomalies.list'))
            ->map(fn($anomaly) =>
            Toggle::make('configuration.' . $anomaly['label'])
                ->label($anomaly['name'])
                ->inline(false)
            )
            ->toArray();
    }
}
