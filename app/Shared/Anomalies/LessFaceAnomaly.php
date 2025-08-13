<?php

namespace App\Shared\Anomalies;

class LessFaceAnomaly extends Anomaly
{
    /**
     * @param array $analyzation
     * @param array $configuration
     * @return bool
     */
    public function check(array $analyzation, array $configuration): bool
    {
        if (empty($analyzation['results'])) return true;

        return count($analyzation['results']) < $configuration['LESS_FACE_COUNT'];
    }
}
