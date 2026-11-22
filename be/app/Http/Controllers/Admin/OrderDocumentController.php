<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment\Order;
use App\Models\Payment\OrderDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class OrderDocumentController extends Controller
{
    public function download(string $orderUuid, string $documentUuid)
    {
        $user = Auth::user();
        $order = Order::packageOnly()->where('uuid', $orderUuid)->firstOrFail();
        $isAdmin = $user->hasAnyRole(['super-admin', 'admin']);
        abort_unless($isAdmin || $order->user_uuid === $user->uuid, 403);

        $document = OrderDocument::where('uuid', $documentUuid)
            ->where('order_uuid', $order->uuid)
            ->firstOrFail();

        $pdf = Pdf::loadView('pages.orders.documents.pdf', compact('document'))
            ->setPaper('a4');

        return $pdf->download(strtolower($document->document_number).'.pdf');
    }
}
