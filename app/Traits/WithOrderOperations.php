<?php

namespace App\Traits;

use App\Jobs\GenerateOrderReceiptPdf;
use App\Jobs\SendEmailToUser;
use App\Models\ItemOrder;
use App\Models\Operation;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait WithOrderOperations
{

    /**
     * Create a new order and its associated items
     *
     * @param array $cartData Cart data from WithCartProcessing
     * @param int $operationId ID of the payment operation
     * @return Order|null The created order or null if creation failed
     */
    public function createOrder(array $cartData, $operationId = null)
    {
        if (!auth()->check()) {
            return null;
        }

        $user = auth()->user();

        try {
            return DB::transaction(function () use ($user, $cartData, $operationId) {
                // Create the order
                $order = new Order();
                $order->member_id = $user->id;
                $order->status = 'pending';
                $order->date = Carbon::now()->toDateString();
                $order->total_items = $cartData['total'];
                $order->shipping_cost = $cartData['shippingCost'];
                $order->total = $cartData['totalWithShipping'];
                $order->nif = $user->nif;
                $order->delivery_address = $user->default_delivery_address;
                $order->save();

                // Create the order items
                foreach ($cartData['cartItems'] as $item) {
                    $product = $item['product'];
                    $itemOrder = new ItemOrder();
                    $itemOrder->order_id = $order->id;
                    $itemOrder->product_id = $product->id;
                    $itemOrder->quantity = $item['quantity'];
                    $itemOrder->unit_price = $item['unitPrice'];
                    $itemOrder->discount = isset($item['discount']) ? $item['discount'] : 0;
                    $itemOrder->subtotal = $item['total'];
                    $itemOrder->save();
                }

                // Update the operation record if provided
                if ($operationId) {
                    $operation = Operation::find($operationId);
                    if ($operation) {
                        $operation->order_id = $order->id;
                        $operation->save();
                    }
                }

                SendEmailToUser::dispatch(
                    $user,
                    'Your Order #' . $order->id . ' Has Been Confirmed',
                    'emails.order-confirmed',
                    ['order' => $order]
                );

                return $order;
            });
        } catch (\Exception $e) {
            // Log the error
            \Illuminate\Support\Facades\Log::error('Order creation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update order status
     *
     * @param int $orderId Order ID
     * @param string $status New status ('pending', 'completed', 'canceled')
     * @param string|null $cancelReason Reason for cancellation (required if status is 'canceled')
     * @return bool Success state
     */
    public function updateOrderStatus($orderId, $status, $cancelReason = null)
    {
        try {
            $order = Order::find($orderId);

            if (!$order) {
                return false;
            }

            // Validate status using constants rather than magic strings
            $validStatuses = ['pending', 'completed', 'canceled'];
            if (!in_array($status, $validStatuses)) {
                return false;
            }

            // Check if cancel reason is provided when required
            if ($status === 'canceled' && empty($cancelReason)) {
                return false;
            }

            DB::transaction(function() use ($order, $status, $cancelReason) {
                $order->status = $status;

                if ($status === 'canceled') {
                    $order->cancel_reason = $cancelReason;
                }

                // Generate receipt and send email if order is completed
                if ($status === 'completed') {
                    // Generate random filename for PDF
                    $randomString = bin2hex(random_bytes(5));
                    $pdfFileName = $order->id . '_' . $randomString . '.pdf';
                    $pdfPath = 'receipts/' . $pdfFileName;

                    // Save PDF filename to order
                    $order->pdf_receipt = $pdfFileName;
                    $order->save();

                    GenerateOrderReceiptPdf::dispatch($order, $order->member, $order->items, $pdfFileName)->chain([
                        new SendEmailToUser(
                            $order->member,
                            'Your Order #' . $order->id . ' Has Been Completed',
                            'emails.order-completed',
                            ['order' => $order],
                            [storage_path('app/private/receipts/' . $pdfFileName)]
                        )
                    ]);
                }

                $order->save();
            });

            return true;

        } catch (\Exception $e) {
            // Log the error with stack trace for better debugging
            \Illuminate\Support\Facades\Log::error('Order status update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order_id' => $orderId,
                'status' => $status
            ]);
            return false;
        }
    }

    /**
     * Get all orders for the authenticated user
     *
     * @param int $limit Number of orders per page
     * @param string|null $status Filter by status
     * @return \Illuminate\Pagination\LengthAwarePaginator|null
     */
    public function getUserOrders($limit = 10, $status = null, $paginate = true)
    {
        if (!auth()->check()) {
            return null;
        }

        $query = Order::where('member_id', auth()->id());

        if ($status) {
            $query->where('status', $status);
        }

        if ($paginate) {
            return $query->with('items.product')
                ->orderBy('created_at', 'desc')
                ->paginate($limit);
        }

        return $query->with('items.product')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAllOrders($paginate = true, $status = null)
    {
        if (!auth()->check()) {
            return null;
        }

        $query = Order::query()
            ->with('items.product')
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        if ($paginate) {
            return $query->paginate(10);
        }

        return $query->get();
    }

    /**
     * Get a specific order with all its details
     *
     * @param int $orderId Order ID
     * @return Order|null
     */
    public function getOrderDetails($orderId)
    {
        if (!auth()->check()) {
            return null;
        }

        $order = Order::where('id', $orderId)
            ->where('member_id', auth()->id())
            ->with(['items.product'])
            ->first();

        return $order;
    }
}
