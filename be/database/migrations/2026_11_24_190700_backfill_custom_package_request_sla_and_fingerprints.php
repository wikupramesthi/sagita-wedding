<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('custom_package_offer_requests')
            ->orderBy('uuid')
            ->chunkById(100, function ($requests) {
                foreach ($requests as $request) {
                    $normalize = static fn ($value) => Str::lower(preg_replace('/\s+/u', ' ', trim((string) $value)) ?? '');
                    $payload = [
                        'event_date' => $request->event_date,
                        'guest_count' => $request->guest_count !== null ? (int) $request->guest_count : null,
                        'venue_note' => $normalize($request->venue_note),
                        'budget_min' => $request->budget_min !== null ? (int) $request->budget_min : null,
                        'budget_max' => $request->budget_max !== null ? (int) $request->budget_max : null,
                        'request_details' => $normalize($request->request_details),
                    ];
                    $updates = [
                        'fingerprint_hash' => hash(
                            'sha256',
                            json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                        ),
                    ];

                    if (in_array($request->status, ['submitted', 'in_review'], true) && ! $request->review_due_at) {
                        $createdAt = $request->created_at
                            ? Carbon::parse($request->created_at)
                            : now();
                        $updates['review_due_at'] = $createdAt->addHours(24);
                    }
                    if ($request->status === 'quoted' && ! $request->quoted_at && $request->handled_at) {
                        $updates['quoted_at'] = $request->handled_at;
                    }

                    DB::table('custom_package_offer_requests')
                        ->where('uuid', $request->uuid)
                        ->update($updates);
                }
            }, 'uuid');
    }

    public function down(): void
    {
        DB::table('custom_package_offer_requests')->update([
            'fingerprint_hash' => null,
            'review_due_at' => null,
            'quoted_at' => null,
        ]);
    }
};
