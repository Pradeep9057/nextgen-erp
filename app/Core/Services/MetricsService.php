<?php

namespace App\Core\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class MetricsService
{
    /**
     * Increment a counter metric.
     * Format: metric_name{label1=val1,label2=val2} value
     */
    public function incrementCounter(string $metricName, array $labels = [], int $value = 1): void
    {
        $labelString = $this->formatLabels($labels);
        $logMessage = "METRIC: {$metricName}{$labelString} {$value}";

        // Log to a dedicated metrics channel for Prometheus scraping
        Log::channel('metrics')->info($logMessage);

        // Also track in cache for a quick "Last Hour" dashboard
        $cacheKey = "metrics:counter:{$metricName}:" . md5($labelString);
        Cache::increment($cacheKey, $value);
    }

    /**
     * Record a gauge value (current state).
     */
    public function recordGauge(string $metricName, array $labels = [], float $value): void
    {
        $labelString = $this->formatLabels($labels);
        $logMessage = "METRIC: {$metricName}{$labelString} {$value}";

        Log::channel('metrics')->info($logMessage);

        $cacheKey = "metrics:gauge:{$metricName}:" . md5($labelString);
        Cache::put($cacheKey, $value, 3600);
    }

    /**
     * Record the duration of an operation (Histogram).
     */
    public function recordDuration(string $metricName, float $durationMs, array $labels = []): void
    {
        $labelString = $this->formatLabels($labels);
        $logMessage = "METRIC: {$metricName}_duration_ms{$labelString} {$durationMs}";

        Log::channel('metrics')->info($logMessage);
    }

    private function formatLabels(array $labels): string
    {
        if (empty($labels)) return '';

        $formatted = [];
        foreach ($labels as $key => $value) {
            $formatted[] = "{$key}=\"{$value}\"";
        }

        return '{' . implode(',', $formatted) . '}';
    }
}
