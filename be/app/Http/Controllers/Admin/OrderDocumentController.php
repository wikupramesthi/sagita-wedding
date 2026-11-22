<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment\Order;
use App\Models\Payment\OrderDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderDocumentController extends Controller
{
    public function preview(string $orderUuid, string $documentUuid)
    {
        $document = $this->authorizedDocument($orderUuid, $documentUuid);

        return $this->pdf($document)
            ->stream($this->fileName($document), ['Attachment' => false]);
    }

    public function download(string $orderUuid, string $documentUuid)
    {
        $document = $this->authorizedDocument($orderUuid, $documentUuid);

        return $this->pdf($document)
            ->download($this->fileName($document));
    }

    private function authorizedDocument(string $orderUuid, string $documentUuid): OrderDocument
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $order = Order::packageOnly()->where('uuid', $orderUuid)->firstOrFail();
        $isAdmin = $user->hasAnyRole(['super-admin', 'admin']);

        abort_unless($isAdmin || $order->user_uuid === $user->uuid, 403);

        return OrderDocument::where('uuid', $documentUuid)
            ->where('order_uuid', $order->uuid)
            ->firstOrFail();
    }

    private function pdf(OrderDocument $document)
    {
        return Pdf::loadView('pages.orders.documents.pdf', compact('document'))
            ->setPaper('a4');
    }

    private function fileName(OrderDocument $document): string
    {
        return Str::lower(Str::slug($document->document_number)).'.pdf';
    }
}
