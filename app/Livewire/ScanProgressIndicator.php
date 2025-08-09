<?php

namespace App\Livewire;

use App\Models\Scan;
use App\Services\ScanProgressIndicatorService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class ScanProgressIndicator extends Component
{
    protected ScanProgressIndicatorService $scanProgressIndicatorService;
    public Scan $scan;
    public array $indicatorData;

    public function __construct()
    {
        $this->scanProgressIndicatorService = new ScanProgressIndicatorService();
    }

    /**
     * @param Scan $scan
     * @return void
     */
    public function mount(Scan $scan): void
    {
        $this->scan = $scan;
        $this->indicatorData = $this->scanProgressIndicatorService->indicate($scan);
    }

    /**
     * @return void
     */
    public function fetchIndicatorData(): void
    {
        $this->indicatorData = $this->scanProgressIndicatorService->indicate($this->scan);
    }

    /**
     * @return Factory|View|Application|object
     */
    public function render()
    {
        return view('livewire.scan-progress-indicator');
    }
}
