<?php

namespace App\Services;

use App\Models\Scan;
use Filament\Forms\Components\Toggle;

class ScanProgressIndicatorService
{
    public const SCAN_FIELDS = [
        'media_frames_data',
        'anomalies_data',
        'violations_data'
    ];

    /**
     * @param Scan $scan
     * @return array
     */
    public static function indicate(Scan $scan): array
    {
        $filled = 0;
        foreach (self::SCAN_FIELDS as $field) {
            if (!empty($scan->{$field})) {
                $filled++;
            }
        }

        $total = count(self::SCAN_FIELDS);
        $percentage = $total > 0 ? round(($filled / $total) * 100, 1) : 0;

        return [
            'total'      => $total,
            'filled'     => $filled,
            'percentage' => $percentage,
        ];
    }
}
