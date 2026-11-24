<?php

return [
    'analytics' => [
        'retention_days' => (int) env('SITA_ANALYTICS_RETENTION_DAYS', 90),
        'max_rows' => (int) env('SITA_ANALYTICS_MAX_ROWS', 5000),
        'prune_chunk' => (int) env('SITA_ANALYTICS_PRUNE_CHUNK', 500),
    ],
];
