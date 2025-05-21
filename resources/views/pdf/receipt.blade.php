<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Order Receipt - {{ $order->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<div class="px-8 py-12 max-w-3xl mx-auto bg-white shadow-lg rounded-lg">
    <div class="flex items-center justify-between mb-12 border-b pb-8">
        <div class="flex items-center">
            <div class="text-emerald-700 font-bold text-2xl tracking-tight">Grocery Club</div>
        </div>
        <div class="text-right">
            <div class="font-extrabold text-2xl mb-3 text-gray-800 tracking-wide">ORDER RECEIPT</div>
            <div class="text-gray-600">Date: {{ date('F d, Y', strtotime($order->date)) }}</div>
            <div class="text-gray-600">Receipt #: {{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>

    <div class="border-b border-gray-200 pb-8 mb-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Customer Details</h2>
        <div class="grid grid-cols-2 gap-8">
            <div>
                <div class="text-gray-600 mb-3"><span class="font-semibold text-gray-700">Name:</span> {{ $user->name }}
                </div>
                <div class="text-gray-600 mb-3"><span
                        class="font-semibold text-gray-700">Email:</span> {{ $user->email }}</div>
                <div class="text-gray-600"><span class="font-semibold text-gray-700">NIF:</span> {{ $order->nif }}</div>
            </div>
            <div>
                <h3 class="font-semibold text-gray-700 mb-2">Shipping Address:</h3>
                <div class="text-gray-600">{{ $order->delivery_address }}</div>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-bold mb-6 text-gray-800">Order Items</h2>
    <table class="w-full mb-8">
        <thead>
        <tr class="border-b-2 border-gray-200">
            <th class="text-left text-gray-600 font-semibold py-3 px-2">Product</th>
            <th class="text-left text-gray-600 font-semibold py-3 px-2">Qty</th>
            <th class="text-right text-gray-600 font-semibold py-3 px-2">Unit Price</th>
            <th class="text-right text-gray-600 font-semibold py-3 px-2">Discount</th>
            <th class="text-right text-gray-600 font-semibold py-3 px-2">Subtotal</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr class="border-b border-gray-100">
                <td class="py-4 px-2 text-gray-800">{{ $item['product']->name }}</td>
                <td class="py-4 px-2 text-gray-600">{{ $item['quantity'] }}</td>
                <td class="py-4 px-2 text-gray-600 text-right">€{{ number_format($item['unitPrice'], 2) }}</td>
                <td class="py-4 px-2 text-gray-600 text-right">€{{ number_format($item['discount'] ?? 0, 2) }}</td>
                <td class="py-4 px-2 text-gray-800 font-medium text-right">€{{ number_format($item['total'], 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="border-t-2 border-gray-200 pt-6 pb-8">
        <div class="flex justify-end text-gray-600 mb-2">
            <div class="w-32">Subtotal:</div>
            <div class="w-32 text-right">€{{ number_format($order->total_items, 2) }}</div>
        </div>
        <div class="flex justify-end text-gray-600 mb-2">
            <div class="w-32">Shipping:</div>
            <div class="w-32 text-right">€{{ number_format($order->shipping_cost, 2) }}</div>
        </div>
        <div class="flex justify-end text-gray-800 font-bold text-xl">
            <div class="w-32">Total:</div>
            <div class="w-32 text-right">€{{ number_format($order->total, 2) }}</div>
        </div>
    </div>
</div>

</body>
</html>
