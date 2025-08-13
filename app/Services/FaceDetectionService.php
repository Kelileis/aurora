<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FaceDetectionService
{
    /**
     * @param string $mediaFramePath
     * @return array
     */
    public static function analyzeMediaFrame(string $mediaFramePath): array
    {
        $response = Http::post(env('FACE_DETECTION_SERVICE_URL') . 'analyze', [
            'img_path' => $mediaFramePath,
            'model_name' => 'VGG-Face',
            "detector_backend" => 'retinaface',
            'distance_metric' => 'cosine',
        ]);

        return $response->json() ?? [];
    }
}
