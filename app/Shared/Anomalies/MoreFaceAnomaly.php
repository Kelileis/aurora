<?php

namespace App\Shared\Anomalies;

use App\Constants\Anomalies;

class MoreFaceAnomaly extends Anomaly
{
    /**
     * @param array $analyzation
     * @param array $configuration
     * @return bool
     */
    public function check(array $analyzation, array $configuration): bool
    {
        if (empty($analyzation['results'])) return false;

        return count($analyzation['results']) >= (int) $configuration[Anomalies::MORE_FACE->value . '_count'];
    }
}
