<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\LaravelPdf\Facades\Pdf;

class GenerateOrderReceiptPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $user;
    protected $cartItems;
    protected $pdfFileName;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, User $user, $cartItems, string $pdfFileName)
    {
        $this->order = $order;
        $this->user = $user;
        $this->cartItems = $cartItems;
        $this->pdfFileName = $pdfFileName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pdfPath = 'receipts/' . $this->pdfFileName;

        Pdf::view('pdf.receipt', [
            'order' => $this->order,
            'user' => $this->user,
            'items' => $this->cartItems
        ])->save(storage_path('app/private/' . $pdfPath));
    }
}
