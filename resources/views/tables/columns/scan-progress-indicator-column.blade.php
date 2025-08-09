@php
    // Filament provides $getRecord() in ViewColumn views
    $scan = $getRecord();
@endphp

<div>
    <livewire:scan-progress-indicator
        :scan="$scan"
        :wire:key="'scan-progress-indicator-'.$scan->id"
    />
</div>
