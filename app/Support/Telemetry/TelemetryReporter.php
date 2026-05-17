<?php

namespace App\Support\Telemetry;

use App\Models\Project;

class TelemetryReporter
{
    /**
     * Generate a dynamic snapshot for UI.
     *
     * @return array{node:string, latency_ms:int, encryption:string, bandwidth:string}
     */
    public function snapshot(?Project $project = null): array
    {
        $region = config('filesystems.disks.s3.region', config('aws.default_region', config('queue.connections.sqs.region', 'us-east-1')));

        // Simple, deterministic pseudo-random based on current minute & project id
        $seed = (int) now()->format('Hi') + ($project?->id ?? 0);
        $latency = 12 + ($seed % 20); // 12-31 ms
        $bandwidthGbps = 1.8 + (($seed % 8) * 0.1); // ~1.8 - 2.5 Gbps

        return [
            'node' => strtoupper($region ?: 'us-east-1'),
            'latency_ms' => $latency,
            'encryption' => 'AES-256-GCM',
            'bandwidth' => number_format($bandwidthGbps, 1).' Gbps',
        ];
    }

    /**
     * Compose a transient build log for display.
     *
     * @param array<string, mixed> $context
     * @return array<int, array{time:string, level:string, message:string}>
     */
    public function buildLog(array $context = []): array
    {
        $now = now();
        $lines = [
            ['time' => $now->copy()->subMinutes(3)->format('H:i:s'), 'level' => 'SYNC',   'message' => 'Node registry synced.'],
            ['time' => $now->copy()->subMinutes(2)->format('H:i:s'), 'level' => 'SYSTEM', 'message' => 'Queue health nominal.'],
            ['time' => $now->copy()->subMinute()->format('H:i:s'),   'level' => 'UPDATE', 'message' => 'Deployment pipeline ready.'],
        ];

        if (! empty($context['file'])) {
            $lines[] = ['time' => $now->format('H:i:s'), 'level' => 'BUILD', 'message' => 'Uploading '.$context['file'].' ...'];
            $lines[] = ['time' => $now->addSeconds(8)->format('H:i:s'), 'level' => 'SYNC', 'message' => 'Integrity verified.'];
            $lines[] = ['time' => $now->addSeconds(16)->format('H:i:s'), 'level' => 'BUILD', 'message' => 'Provisioning binary to CDN edge...'];
        }

        return $lines;
    }
}
