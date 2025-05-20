<!DOCTYPE html>
<html>
<head>
    <title>Order Completed</title>
    <style>
        body {
            background-color: #f9fafb;
            font-family: sans-serif;
            color: #1f2937;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 42rem;
            margin: 0 auto;
            padding: 2rem;
        }
        .header {
            text-align: center;
            padding: 2rem 0;
            border-bottom: 2px solid #e5e7eb;
        }
        .header h1 {
            font-size: 1.875rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .header p {
            font-size: 1.125rem;
            margin: 0;
        }
        .order-info {
            margin: 2rem 0;
            padding: 1.5rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .order-info h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .order-info p {
            margin-bottom: 0.5rem;
        }
        .order-info .label {
            font-weight: 600;
        }
        table {
            width: 100%;
            margin-bottom: 2rem;
            border-collapse: collapse;
        }
        th {
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
        }
        td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
        }
        tfoot td {
            font-weight: 600;
        }
        .footer {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #4b5563;
            font-size: 0.875rem;
        }
        .footer p {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Completed</h1>
            <p>Thank you for your order!</p>
        </div>

        <div class="order-info">
            <h2>Order Information</h2>
            <p><span class="label">Order ID:</span> {{ $order->id }}</p>
            <p><span class="label">Order Date:</span> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><span class="label">Delivery Address:</span> {{ $order->delivery_address }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>€{{ number_format($item->unit_price, 2) }}</td>
                    <td>€{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Shipping Cost:</td>
                    <td>€{{ number_format($order->shipping_cost, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3">Total:</td>
                    <td>€{{ number_format($order->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <p>If you have any questions about your order, please contact our customer service.</p>
            <p>Thank you for shopping with us!</p>
        </div>
    </div>
</body>
</html>
