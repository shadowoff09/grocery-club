<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Masmerise\Toaster\Toaster;

class ReceiptController extends Controller
{

    use AuthorizesRequests;

    public function show($orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->pdf_receipt === null) {
            Toaster::error('Recibo não encontrado');
            $previousUrl = url()->previous();
            return redirect()->to($previousUrl);
        }

        $this->authorize('view', $order);

        $relativePath = 'receipts/' . $order->pdf_receipt;

        if (!Storage::disk('local')->exists($relativePath)) {
            abort(404, "Recibo não encontrado em storage/app/private/{$relativePath}");
        }

        return Storage::disk('local')->response(
            $relativePath,
            'Receipt #' . $order->id . '.pdf',
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $order->id . '.pdf"',
            ]
        );
    }
}
