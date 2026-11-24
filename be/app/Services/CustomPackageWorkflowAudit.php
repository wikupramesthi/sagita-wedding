<?php

namespace App\Services;

use App\Models\Package\CustomPackageWorkflowEvent;

class CustomPackageWorkflowAudit
{
    public function record(
        ?string $actorUuid,
        string $eventKey,
        ?string $requestUuid = null,
        ?string $offerUuid = null,
        ?string $fromStatus = null,
        ?string $toStatus = null,
        array $details = []
    ): CustomPackageWorkflowEvent {
        return CustomPackageWorkflowEvent::create([
            'actor_uuid' => $actorUuid,
            'request_uuid' => $requestUuid,
            'offer_uuid' => $offerUuid,
            'event_key' => $eventKey,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'details' => $details ?: null,
        ]);
    }
}
