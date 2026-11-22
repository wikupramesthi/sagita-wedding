<?php

return [

    'enabled' => (bool) env('WHATSAPP_ENABLED', false),

    'driver' => env('WHATSAPP_DRIVER', 'log'),

    'api_url' => env('WHATSAPP_API_URL', ''),

    'token' => env('WHATSAPP_TOKEN', ''),

    'from' => env('WHATSAPP_FROM', ''),

    'timeout' => (int) env('WHATSAPP_TIMEOUT', 10),

    'retry' => (int) env('WHATSAPP_RETRY', 2),

    'admin_number' => env('MANUAL_ADMIN_WA', ''),

];
