<?php

namespace App\Shared\Anomalies;

class MoreFaceAnomaly extends Anomaly
{
    /**
     * @param array $analyzation
     * @param array $configuration
     * @return bool
     */
    public function check(array $analyzation, array $configuration): bool
    {
        return empty($analyzation['results']);
    }
}
