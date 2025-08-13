<?php

namespace App\Shared\Anomalies;

use App\Models\Scan;

interface AnomalyInterface
{
    public function check(array $analyzation, array $configuration): bool;
}
