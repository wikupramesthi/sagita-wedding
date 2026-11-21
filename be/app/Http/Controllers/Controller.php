<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Throwable;

abstract class Controller
{
    protected function safeErrorMessage(Throwable $e): string
    {
        Log::warning('Detail kesalahan: '.$e->getMessage(), [
            'exception' => get_class($e),
        ]);

        return 'Gagal menyimpan data. Silakan coba lagi; detail teknis ada di log.';
    }
}
