<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package\CustomPackageOffer;
use App\Models\Package\CustomPackageOfferRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CustomPackageOfferAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(
            auth()->user()?->hasAnyRole(['super-admin', 'admin']) &&
            auth()->user()->can('package.index'),
            404
        );

        $validated = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);
        $month = $validated['month'] ?? now()->format('Y-m');
        $start = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $requests = CustomPackageOfferRequest::query()
            ->whereBetween('created_at', [$start, $end])
            ->get(['status', 'created_at', 'quoted_at']);
        $sentOffers = CustomPackageOffer::query()
            ->whereNotNull('sent_at')
            ->whereBetween('sent_at', [$start, $end])
            ->with(['orders'])
            ->get(['uuid', 'status', 'sent_at', 'accepted_at', 'rejected_at', 'rejection_reason']);

        $requestStatuses = $requests->countBy('status');
        $sentCount = $sentOffers->count();
        $acceptedCount = $sentOffers->whereNotNull('accepted_at')->count();
        $relatedOrders = $sentOffers->flatMap(fn (CustomPackageOffer $offer) => $offer->orders);
        $offersWithOrders = $sentOffers->filter(fn (CustomPackageOffer $offer) => $offer->orders->isNotEmpty())->count();
        $orderCount = $relatedOrders->count();
        $paidOrderCount = $relatedOrders->whereIn('status', ['partially_paid', 'paid'])->count();
        $acceptedToOrderRate = $acceptedCount > 0
            ? round($offersWithOrders / $acceptedCount * 100, 1)
            : 0;
        $orderPaymentRate = $orderCount > 0
            ? round($paidOrderCount / $orderCount * 100, 1)
            : 0;
        $receivedAmount = $relatedOrders->sum(fn ($order) => in_array($order->status, ['partially_paid', 'paid'], true)
            ? (float) $order->amount_paid_value
            : 0);
        $rejectedOffers = $sentOffers->whereNotNull('rejected_at');
        $rejectionReasons = $rejectedOffers
            ->countBy(fn ($offer) => $offer->rejection_reason ?: 'other')
            ->sortDesc();

        $quoteTimes = $requests
            ->filter(fn ($row) => $row->quoted_at)
            ->map(fn ($row) => Carbon::parse($row->created_at)->diffInMinutes(Carbon::parse($row->quoted_at)));
        $responseTimes = $sentOffers
            ->filter(fn ($offer) => $offer->accepted_at)
            ->map(fn ($offer) => Carbon::parse($offer->sent_at)->diffInMinutes(Carbon::parse($offer->accepted_at)));

        $reasonLabels = [
            'price' => 'Anggaran atau harga',
            'services' => 'Layanan yang ditawarkan',
            'date' => 'Tanggal atau jadwal',
            'other' => 'Alasan lainnya',
        ];

        return view('pages.package-offers.analytics', [
            'month' => $month,
            'monthLabel' => $start->translatedFormat('F Y'),
            'requestCount' => $requests->count(),
            'requestStatuses' => $requestStatuses,
            'sentCount' => $sentCount,
            'acceptedCount' => $acceptedCount,
            'rejectedCount' => $rejectedOffers->count(),
            'orderCount' => $orderCount,
            'paidOrderCount' => $paidOrderCount,
            'acceptedToOrderRate' => $acceptedToOrderRate,
            'orderPaymentRate' => $orderPaymentRate,
            'receivedAmount' => $receivedAmount,
            'conversionRate' => $sentCount > 0 ? round($acceptedCount / $sentCount * 100, 1) : 0,
            'averageQuoteHours' => $quoteTimes->isNotEmpty() ? round($quoteTimes->avg() / 60, 1) : null,
            'averageResponseHours' => $responseTimes->isNotEmpty() ? round($responseTimes->avg() / 60, 1) : null,
            'rejectionReasons' => $rejectionReasons,
            'reasonLabels' => $reasonLabels,
        ]);
    }
}
