<?php

namespace App\Core\Traits;

use App\Core\Services\MetricsService;
use Illuminate\Support\Facades\Log;

trait Observable
{
    protected function trackMetric(string $name, array $labels = [], int $value = 1): void
    {
        if (app()->bound(MetricsService::class)) {
            app(MetricsService::class)->incrementCounter($name, $labels, $value);
        }
    }

    protected function trackDuration(string $name, float $start, array $labels = []): void
    {
        $duration = (microtime(true) - $start) * 1000;
        if (app()->bound(MetricsService::class)) {
            app(MetricsService::class)->recordDuration($name, $duration, $labels);
        }
    }
}
