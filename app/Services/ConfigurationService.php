<?php

namespace App\Services;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;

class ConfigurationService
{
    public static function constructForm(): array
    {
        return collect(config('app.anomalies.list'))
            ->map(function ($anomaly) {
                // Special handling for MORE_FACE / LESS_FACE
                if (in_array($anomaly['label'], ['MORE_FACE', 'LESS_FACE'])) {
                    // Choose label text depending on anomaly
                    $countLabel = $anomaly['label'] === 'MORE_FACE'
                        ? 'At least'
                        : 'Less than';

                    return Grid::make()
                        ->columns(12)
                        ->schema([
                            Toggle::make('configuration.' . $anomaly['label'])
                                ->label($anomaly['name'])
                                ->inline(false)
                                ->live() // so visibility updates instantly
                                ->columnSpan(6),

                            TextInput::make('configuration.' . $anomaly['label'] . '_COUNT')
                                ->label($countLabel . ' (faces)')
                                ->numeric()
                                ->minValue(1)
                                ->visible(fn (Get $get) => (bool) $get('configuration.' . $anomaly['label']))
                                ->columnSpan(6),
                        ]);
                }

                // Default: single toggle
                return Toggle::make('configuration.' . $anomaly['label'])
                    ->label($anomaly['name'])
                    ->inline(false);
            })
            ->all();
    }
}
