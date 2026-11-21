<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Package\Package;
use App\Models\Package\PackagePromo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class QuotationController extends Controller
{
    public function package(Request $request, string $packageUuid, bool $download = false)
    {
        $package = Package::with([
            'options' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('sort_order');
            },
            'addons' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('sort_order');
            },
            'features',
            'promos' => function ($query) {
                $query->usable()
                    ->orderBy('sort_order');
            },
        ])
            ->where('uuid', $packageUuid)
            ->published()
            ->firstOrFail();

        $optionUuid = $request->input('option_uuid');
        $option = is_string($optionUuid)
            ? $package->options->firstWhere('uuid', $optionUuid)
            : null;
        $option = $option ?? $package->options->first();

        $quantities = $request->input('addon_quantities');
        $quantities = is_array($quantities) ? $quantities : [];

        $addonUuids = $request->input('addon_uuids');
        $addonUuids = is_array($addonUuids) ? $addonUuids : [];

        $addons = collect();

        foreach (array_unique(array_filter($addonUuids, fn ($uuid) => ! blank($uuid))) as $uuid) {
            $source = $package->addons->firstWhere('uuid', $uuid);

            if (! $source) {
                continue;
            }

            $min = max(1, (int) ($source->min_quantity ?? 1));
            $max = $source->max_quantity !== null
                ? min(99, (int) $source->max_quantity)
                : 99;
            $quantity = (int) ($quantities[$uuid] ?? $min);
            $quantity = min(max($quantity, $min), max($min, $max));

            $addon = clone $source;
            $addon->quantity = $quantity;
            $addon->line_total = (int) $source->final_price * $quantity;

            $addons->push($addon);
        }

        $subtotal = $option
            ? (int) ($option->discount_price !== null && $option->discount_price < $option->price
                ? $option->discount_price
                : $option->price)
            : 0;

        $subtotal += (int) $addons->sum('line_total');

        $discount = 0;
        $promoInput = $request->input('promo_code');
        $promoInput = is_string($promoInput) ? trim($promoInput) : '';

        if ($promoInput !== '') {
            $promo = PackagePromo::whereRaw('UPPER(code)=UPPER(?)', [strtoupper($promoInput)])
                ->usable()
                ->first();

            if (
                $promo &&
                ($promo->package_uuid === null || $promo->package_uuid === $package->uuid)
            ) {
                $discount = $promo->discountFor((int) $subtotal);
            }
        }

        $total = max(0, $subtotal - $discount);

        $eventDate = null;
        $eventDateInput = $request->input('event_date');

        if (
            is_string($eventDateInput) &&
            preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $eventDateInput, $dateParts) &&
            checkdate((int) $dateParts[2], (int) $dateParts[3], (int) $dateParts[1])
        ) {
            $eventDate = Carbon::createFromFormat('Y-m-d', $eventDateInput)->startOfDay();
        }

        $phone = Consultation::normalizeWhatsapp(config('manual_payment.admin_whatsapp'));

        $message = 'Halo Sagita Wedding Organizer, saya ingin meminta penawaran paket '
            .$package->name
            .'. Opsi: '.($option?->name ?? '-')
            .'. Tanggal acara: '.($eventDate?->translatedFormat('d F Y') ?? 'belum ditentukan')
            .'. Layanan tambahan: '.($addons->isNotEmpty()
                ? $addons->map(fn ($addon) => $addon->name.' x'.$addon->quantity)->implode(', ')
                : 'tidak ada')
            .'. Total: Rp '.number_format($total, 0, ',', '.')
            .'. Mohon ditindaklanjuti, terima kasih.';

        $waUrl = $phone
            ? 'https://wa.me/'.$phone.'?text='.rawurlencode($message)
            : null;

        $pdf = Pdf::loadView('pages.package.quotation', compact(
            'package',
            'option',
            'addons',
            'subtotal',
            'discount',
            'total',
            'waUrl',
            'eventDate'
        ));

        $filename = 'penawaran-'.$package->slug.'.pdf';

        return $download ? $pdf->download($filename) : $pdf->stream($filename);
    }

    public function download(Request $request, string $package)
    {
        return $this->package($request, $package, true);
    }
}
