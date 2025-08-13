<?php

namespace App\Services;

use App\Models\Scan;
use App\Shared\Anomalies\Anomaly;
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

        var_dump($configuration);

        foreach ($configuration as $anomalyName => $enabled) {
            if (!$enabled) continue;

            var_dump($anomalyName);
            var_dump($enabled);

            // Do the anomaly check
            foreach ($mediaFrameAnalyzations as $mediaFramePath => $mediaFrameAnalyzation) {
                if (empty($anomaliesData[$mediaFramePath])) {
                    $anomaliesData[$mediaFramePath] = [];
                }

                $anomaly = $this->getAnomalyByName($anomalyName);

                var_dump(get_class($anomaly));

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
            'NO_FACE' => new NoFaceAnomaly(),
            default => null,
        };
    }
}
