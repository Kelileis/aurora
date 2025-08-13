<?php

namespace App\Constants;

enum Queues: string
{
    case MEDIA_CUTTING_QUEUE = 'media_cutting';
    case MEDIA_FRAME_ANALYZING_QUEUE = 'media_frame_analyzing';
    case ANOMALY_DETECTION_QUEUE = 'anomaly_detection';
}
