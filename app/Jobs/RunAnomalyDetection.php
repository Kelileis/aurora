<?php

namespace App\Jobs;

use App\Models\Scan;
use App\Services\AnomalyService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunAnomalyDetection implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Scan $scan;

    public function __construct(Scan $scan)
    {
        $this->scan = $scan;
    }

    /**
     */
    public function handle(): void
    {
        var_dump('HELLO');

        (new AnomalyService())->run($this->scan);

        var_dump('DONE ANOMALYING');
    }
}
