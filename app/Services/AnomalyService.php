<?php

namespace App\Services;

use App\Constants\Anomalies;
use App\Models\Scan;
use App\Shared\Anomalies\Anomaly;
use App\Shared\Anomalies\LessFaceAnomaly;
use App\Shared\Anomalies\MoreFaceAnomaly;
use App\Shared\Anomalies\NoFaceAnomaly;

class AnomalyService
{
    /**
     * @param Scan $scan
     * @return void
     */
    public function run(Scan $scan): void
    {
        $configuration = $scan->configuration;
        $mediaFrameAnalyzations = $scan->media_frames_analyzation_data;

        $anomaliesData = [];

        foreach ($configuration as $anomalyName => $enabled) {
            if (!$enabled) continue;

            // Do the anomaly check
            foreach ($mediaFrameAnalyzations as $mediaFramePath => $mediaFrameAnalyzation) {
                if (empty($anomaliesData[$mediaFramePath])) {
                    $anomaliesData[$mediaFramePath] = [];
                }

                $anomaly = $this->getAnomalyByName($anomalyName);

                if (empty($anomaly)) continue;

                if ($anomaly->check($mediaFrameAnalyzation, $configuration)) {
                    $anomaliesData[$mediaFramePath][] = $anomalyName;
                }
            }
        }

        $scan->anomalies_data = json_encode($anomaliesData);
        $scan->save();
    }

    /**
     * @param string $anomalyName
     * @return Anomaly|null
     */
    private function getAnomalyByName(string $anomalyName): ?Anomaly
    {
        return match ($anomalyName) {
            Anomalies::NO_FACE->value => new NoFaceAnomaly(),
            Anomalies::LESS_FACE->value => new LessFaceAnomaly(),
            Anomalies::MORE_FACE->value => new MoreFaceAnomaly(),
            default => null,
        };
    }
}
