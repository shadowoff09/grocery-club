<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">My Orders</h1>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">View and manage your order history</p>
        </div>

        <!-- Status Filter -->
        <div class="mb-6 bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-4">
            <div class="flex flex-wrap gap-2">
                <button wire:click="filterByStatus('all')"
                   class="px-4 py-2 text-sm rounded-lg transition-colors {{ $statusFilter === null ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 font-medium' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                   All Orders
                </button>
                <button wire:click="filterByStatus('pending')"
                   class="px-4 py-2 text-sm rounded-lg transition-colors {{ $statusFilter === 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200 font-medium' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                   Pending
                </button>
                <button wire:click="filterByStatus('completed')"
                   class="px-4 py-2 text-sm rounded-lg transition-colors {{ $statusFilter === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200 font-medium' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                   Completed
                </button>
                <button wire:click="filterByStatus('canceled')"
                   class="px-4 py-2 text-sm rounded-lg transition-colors {{ $statusFilter === 'canceled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 font-medium' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                   Canceled
                </button>
            </div>
        </div>

        <!-- Orders List -->
        @if($orders && $orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm overflow-hidden">
                        <!-- Order Header -->
                        <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800 flex flex-wrap justify-between items-center gap-4">
                            <div>
                                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">
                                    Order #{{ $order->id }}
                                </h2>
                                <div class="flex items-center gap-3 mt-1 text-sm">
                                    <span class="text-zinc-500 dark:text-zinc-400 font-medium">
                                        <time datetime="{{ $order->created_at->toIso8601String() }}"
                                              class="whitespace-nowrap">
                                            {{ $order->created_at->format('d/m/Y') }}
                                            <span class="text-zinc-400 dark:text-zinc-500 font-normal">({{ $order->created_at->diffForHumans() }})</span>
                                        </time>
                                    </span>
                                    <span class="text-zinc-300 dark:text-zinc-600">•</span>
                                    <span class="text-zinc-500 dark:text-zinc-400">{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</span>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="mr-4 text-right">
                                    <div class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">${{ number_format($order->total, 2) }}</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                        Items: ${{ number_format($order->total_items, 2) }} +
                                        Shipping: ${{ number_format($order->shipping_cost, 2) }}
                                    </div>
                                </div>

                                @if($order->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                        <svg class="mr-1.5 h-2 w-2 text-amber-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Pending
                                    </span>
                                @elseif($order->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                        <svg class="mr-1.5 h-2 w-2 text-emerald-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Completed
                                    </span>
                                @elseif($order->status === 'canceled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        <svg class="mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Canceled
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                            @foreach($order->items as $item)
                                <div class="p-5 flex items-start gap-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0 bg-zinc-100 dark:bg-zinc-800 rounded-lg overflow-hidden w-16 h-16 p-0 shadow-sm">
                                        @if($item->product && $item->product->photo)
                                            <img src="{{ asset('storage/products/' . $item->product->photo) }}"
                                                alt="{{ $item->product->name }}"
                                                class="object-cover w-full h-full">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 mb-1">
                                            {{ $item->product ? $item->product->name : 'Product not available' }}
                                        </h3>
                                        <div class="flex items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400 mb-2">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                                <span>Qty: {{ $item->quantity }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>${{ number_format($item->unit_price, 2) }} each</span>
                                            </div>
                                        </div>

                                        @if($item->discount > 0)
                                            <div class="inline-flex items-center mt-1 text-xs px-2 py-0.5 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                </svg>
                                                {{ number_format($item->discount, 0) }}% off
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Price -->
                                    <div class="text-right">
                                        <div class="text-base font-semibold text-zinc-900 dark:text-zinc-100">${{ number_format($item->subtotal, 2) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Footer -->
                        <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 border-t border-zinc-200 dark:border-zinc-800 flex flex-wrap justify-between">
                            <div>
                                <div class="text-sm text-zinc-600 dark:text-zinc-400">
                                    <strong>Delivery address:</strong> {{ $order->delivery_address }}
                                </div>
                                @if($order->nif)
                                    <div class="text-sm text-zinc-600 dark:text-zinc-400">
                                        <strong>NIF:</strong> {{ $order->nif }}
                                    </div>
                                @endif
                            </div>

                            <div class="mt-2 sm:mt-0">
                                @if($order->status === 'pending')
                                    <a href="{{ route('order.confirmation', ['order_id' => $order->id]) }}"
                                       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View Details
                                    </a>
                                @endif

                                @if($order->pdf_receipt)
                                        <a href="{{ route('receipts.show', $order->id) }}"
                                       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        Receipt
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-8 text-center">
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-indigo-100 dark:bg-indigo-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h3 class="mt-6 text-xl font-medium text-zinc-900 dark:text-zinc-100">No orders found</h3>
                <p class="mt-2 text-zinc-600 dark:text-zinc-400">You haven't placed any orders yet.</p>
                <div class="mt-6">
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Browse catalog
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
