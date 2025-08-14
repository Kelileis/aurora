<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class FaceDetectionService
{
    /**
     * @param array $configuration
     * @param string $mediaFramePath
     * @return array
     */
    public static function analyzeMediaFrame(array $configuration, string $mediaFramePath): array
    {
        $response = self::analyze($mediaFramePath);

        return $response->json() ?? [];
    }

    /**
     * @param $mediaFramePath
     * @return Response
     */
    private static function analyze($mediaFramePath): Response
    {
        return Http::post(env('FACE_DETECTION_SERVICE_URL') . 'analyze', [
            'img_path' => $mediaFramePath,
            'model_name' => 'VGG-Face',
            "detector_backend" => 'retinaface',
            'distance_metric' => 'cosine',
        ]);
    }
}
