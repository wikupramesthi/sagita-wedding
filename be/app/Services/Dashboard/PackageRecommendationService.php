<?php

namespace App\Services\Dashboard;

use App\Models\Package\Package;
use Illuminate\Support\Collection;

class PackageRecommendationService
{
    public function forBudget(int $budgetMax): Collection
    {
        return Package::published()
            ->wedding()
            ->with(['options' => fn ($query) => $query
                ->where('is_active', true)
                ->orderBy('sort_order')])
            ->get()
            ->map(fn (Package $package) => $this->bestOption($package, $budgetMax))
            ->filter()
            ->sortBy('price')
            ->take(6)
            ->values();
    }

    public function toPayload(Collection $recommendations): Collection
    {
        return $recommendations->map(fn (array $recommendation) => [
            'name' => $recommendation['package']->name,
            'option' => $recommendation['option']->name,
            'price' => $recommendation['price'],
            'formatted_price' => number_format($recommendation['price'], 0, ',', '.'),
            'checkout_url' => route('checkout.package', $recommendation['package']->uuid),
        ]);
    }

    private function bestOption(Package $package, int $budgetMax): ?array
    {
        $matchingOptions = $package->options
            ->filter(fn ($option) => $this->effectivePrice($option) <= $budgetMax)
            ->sortBy(fn ($option) => $this->effectivePrice($option))
            ->values();

        if ($matchingOptions->isEmpty()) {
            return null;
        }

        $option = $matchingOptions->first();

        return [
            'package' => $package,
            'option' => $option,
            'price' => $this->effectivePrice($option),
        ];
    }

    private function effectivePrice($option): int
    {
        return min(
            (int) $option->price,
            (int) ($option->discount_price ?? $option->price)
        );
    }
}
