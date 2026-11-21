<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'uuid' => $this->uuid,

            'name' => $this->name,

            'slug' => $this->slug,

            'description' => $this->description,

            'guest_capacity' => $this->guest_capacity,

            'event_duration_hours' => $this->event_duration_hours,

            'venue_note' => $this->venue_note,

            'cover_url' => $this->cover_url,

            'is_popular' => (bool) $this->is_popular,

            'lead_time_days' => (int) $this->lead_time_days,

            'payment_schemes' => [
                ['code' => 'dp30', 'label' => 'DP 30%', 'factor' => 0.30],
                ['code' => 'dp50', 'label' => 'DP 50%', 'factor' => 0.50],
                ['code' => 'full', 'label' => 'Bayar Lunas', 'factor' => 1.00],
            ],

            'blackout_dates' => $this->whenLoaded('blackoutDates', fn () => $this->blackoutDates->map(fn ($d) => [
                'date' => $d->date?->toDateString(),
                'reason' => $d->reason,
            ])->values(), []),

            'promos' => $this->whenLoaded('promos', fn () => $this->promos->filter(fn ($p) => $p->isUsable())->map(fn ($p) => [
                'code' => $p->code,
                'type' => $p->type,
                'value' => (int) $p->value,
                'min_purchase' => (int) $p->min_purchase,
                'max_discount' => $p->max_discount !== null ? (int) $p->max_discount : null,
            ])->values(), []),

            'options' => $this->options->map(function ($option) {

                $finalPrice = (
                    $option->discount_price !== null &&
                    $option->discount_price < $option->price
                )
                    ? $option->discount_price
                    : $option->price;

                return [

                    'uuid' => $option->uuid,

                    'name' => $option->name,

                    'quota' => $option->quota,

                    'price' => $option->price,

                    'discount_price' => $option->discount_price,

                    'final_price' => $finalPrice,

                    'duration' => $option->duration,

                    'duration_unit' => $option->duration_unit,

                    'sort_order' => $option->sort_order,

                ];
            }),

            'addons' => $this->addons->map(function ($addon) {

                $finalPrice = (
                    $addon->discount_price !== null &&
                    $addon->discount_price < $addon->price
                )
                    ? $addon->discount_price
                    : $addon->price;

                return [

                    'uuid' => $addon->uuid,

                    'name' => $addon->name,

                    'description' => $addon->description,

                    'price' => $addon->price,

                    'discount_price' => $addon->discount_price,

                    'final_price' => $finalPrice,

                    'min_quantity' => (int) ($addon->min_quantity ?? 1),

                    'max_quantity' => $addon->max_quantity !== null
                        ? (int) $addon->max_quantity
                        : null,

                    'requires_addon_uuid' => $addon->requires_addon_uuid,

                    'sort_order' => $addon->sort_order,

                ];
            }),

            'features' => $this->features->map(function ($feature) {

                return [

                    'uuid' => $feature->uuid,

                    'feature' => $feature->feature,

                    'sort_order' => $feature->sort_order,

                ];
            }),

        ];
    }
}
