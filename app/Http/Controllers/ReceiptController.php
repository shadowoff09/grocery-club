<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller
{

    use AuthorizesRequests;

    public function show($orderId)
    {
        $order = Order::findOrFail($orderId);

        // 1) Autorização
        $this->authorize('view', $order);

        // 2) Caminho RELATIVO a storage/app/private!
        $relativePath = 'receipts/' . $order->pdf_receipt;

        // 3) Verifica existência SEMPRE no disco 'local'
        if (!Storage::disk('local')->exists($relativePath)) {
            abort(404, "Recibo não encontrado em storage/app/private/{$relativePath}");
        }

        // 4) Serve o ficheiro do MESMO disco
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
