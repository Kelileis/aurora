<?php

namespace App\Shared\Anomalies;

use App\Models\Scan;

abstract class Anomaly implements AnomalyInterface
{
    /**
     * @param array $analyzation
     * @param array $configuration
     * @return bool
     */
    public function check(array $analyzation, array $configuration): bool
    {
        return false;
    }
}
