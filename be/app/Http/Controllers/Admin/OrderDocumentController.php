<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment\Order;
use App\Models\Payment\OrderDocument;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function accept(Request $request, string $orderUuid, string $documentUuid)
    {
        $request->validate(['agreement' => ['accepted']]);

        $document = $this->authorizedDocument($orderUuid, $documentUuid);
        abort_unless($document->type === 'contract', 404);
        abort_unless($document->order?->user_uuid === Auth::user()?->uuid, 403);

        $accepted = DB::transaction(function () use ($document): bool {
            $lockedDocument = OrderDocument::where('uuid', $document->uuid)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedDocument->accepted_at) {
                return false;
            }

            $lockedDocument->forceFill([
                'accepted_at' => now(),
                'accepted_by_uuid' => Auth::user()->uuid,
                'accepted_snapshot' => $lockedDocument->snapshot,
            ])->save();

            return true;
        });

        if (! $accepted) {
            return back()->with('success', 'Persetujuan kontrak Anda sudah tercatat.');
        }

        return back()->with('success', 'Persetujuan kontrak berhasil dicatat beserta salinan dokumen yang Anda setujui.');
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
        if ($document->type === 'contract' && $document->accepted_snapshot !== null) {
            $document->setAttribute('snapshot', $document->accepted_snapshot);
        }

        return Pdf::loadView('pages.orders.documents.pdf', compact('document'))
            ->setPaper('a4');
    }

    private function fileName(OrderDocument $document): string
    {
        return Str::lower(Str::slug($document->document_number)).'.pdf';
    }
}
