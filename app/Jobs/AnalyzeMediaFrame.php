<?php

namespace App\Jobs;

use App\Constants\Queues;
use App\Models\Scan;
use App\Services\FaceDetectionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AnalyzeMediaFrame implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Scan $scan;
    protected string $mediaFrame;

    public function __construct(Scan $scan, string $mediaFrame)
    {
        $this->scan = $scan;
        $this->mediaFrame = $mediaFrame;
    }

    /**
     */
    public function handle(): void
    {
        $response = FaceDetectionService::analyzeMediaFrame(asset('storage') . '/' . $this->mediaFrame);

        // Prepare JSON fragment to merge
        $patch = json_encode([
            $this->mediaFrame => $response,
        ]);

        // Use atomic JSON_MERGE_PATCH to update the JSON column in one query
        DB::table('scans')
            ->where('id', $this->scan->id)
            ->update([
                'media_frames_analyzation_data' => DB::raw("JSON_MERGE_PATCH(media_frames_analyzation_data, '$patch')")
            ]);

        $result = DB::selectOne('
            SELECT
                JSON_LENGTH(media_frames_analyzation_data) AS analyzation_count,
                JSON_LENGTH(media_frames_data) AS media_frames_count
            FROM scans WHERE id = ?
        ', [$this->scan->id]);

        /**
         * All the frames have been processed
         */
        if ($result && $result->analyzation_count === $result->media_frames_count) {
            var_dump('processed');
            RunAnomalyDetection::dispatch($this->scan)
                ->onConnection('redis')
                ->onQueue(Queues::ANOMALY_DETECTION_QUEUE);
        }
    }
}
