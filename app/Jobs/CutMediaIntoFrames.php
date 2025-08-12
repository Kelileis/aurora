<?php

namespace App\Jobs;

use App\Constants\Queues;
use App\Models\Scan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class CutMediaIntoFrames implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Scan $scan;

    public function __construct(Scan $scan)
    {
        $this->scan = $scan;
    }

    public function handle(): void
    {
        /**
         * Extract frames every 3 frames per second
         * %04d.jpg will name frames like 0001.jpg, 0002.jpg, etc.
         */
        FFMpeg::fromDisk('public')
            ->open($this->scan->media->path)
            ->exportFramesByInterval(1)
            ->toDisk('public')
            ->save('scans/' . $this->scan->id . '/media_frames/%04d.jpg');

        $mediaFrameFiles = Storage::disk('public')->allFiles('scans/' . $this->scan->id . '/media_frames');

        $this->scan->media_frames_data = json_encode($mediaFrameFiles);
        $this->scan->save();

        foreach ($mediaFrameFiles as $mediaFrameFile) {
            AnalyzeMediaFrame::dispatch($this->scan, $mediaFrameFile)
                ->onConnection('redis')
                ->onQueue(Queues::MEDIA_FRAME_ANALYZING_QUEUE);
        }
    }
}
