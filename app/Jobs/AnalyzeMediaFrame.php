<?php

namespace App\Jobs;

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
    }
}
