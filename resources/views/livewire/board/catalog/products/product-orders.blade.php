<div class="min-h-screen">
    <div class="container mx-auto px-2 py-2">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('board.catalog.products') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Products</a>
                <x-lucide-chevron-right class="w-4 h-4" />
                <a href="{{ route('board.catalog.products.show', $product->id) }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">{{ $product->name }}</a>
                <x-lucide-chevron-right class="w-4 h-4" />
                <span class="text-gray-900 dark:text-white font-medium">Orders</span>
            </div>
            <div class="flex items-center gap-3">
                <flux:button class="cursor-pointer" variant="primary" icon="arrow-left" href="{{ route('board.catalog.products.show', $product->id) }}">
                    Back to Product
                </flux:button>
            </div>
        </div>

        <!-- Product Info Card -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden mb-8">
            <div class="p-6">
                <div class="flex items-start gap-6">
                    <!-- Product Image -->
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 dark:bg-zinc-800">
                            @if($product->photo)
                                <img src="{{ asset('storage/products/' . $product->photo) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400 dark:text-gray-600">
                                    <x-lucide-image class="w-8 h-8" />
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $product->name }}</h1>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $product->description }}</p>
                                @if($product->category)
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400">
                                            {{ $product->category->name }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($product->price, 2) }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Stock: {{ $product->stock }}</div>
                                @if($product->trashed())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400 mt-2">
                                        Deleted
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Orders History</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">All orders containing this product</p>
                    </div>
                    @if($orders->total() > 0)
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $orders->total() }} {{ Str::plural('order', $orders->total()) }} found
                        </div>
                    @endif
                </div>

                @if($orders && $orders->count() > 0)
                    <div class="space-y-4">
                        @foreach($orders as $order)
                            <div class="border border-gray-200 dark:border-zinc-700 rounded-lg overflow-hidden">
                                <!-- Order Header -->
                                <div class="px-4 py-3 bg-gray-50 dark:bg-zinc-800 border-b border-gray-200 dark:border-zinc-700">
                                    <div class="flex flex-wrap justify-between items-center gap-4">
                                        <div>
                                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                                Order #{{ $order->id }}
                                            </h3>
                                            <div class="flex items-center gap-3 mt-1 text-sm">
                                                <span class="text-gray-500 dark:text-gray-400 font-medium">
                                                    <time datetime="{{ $order->created_at->toIso8601String() }}">
                                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                                        <span class="text-gray-400 dark:text-gray-500 font-normal">({{ $order->created_at->diffForHumans() }})</span>
                                                    </time>
                                                </span>
                                                <span class="text-gray-300 dark:text-gray-600">•</span>
                                                <span class="text-gray-500 dark:text-gray-400">{{ $order->member->email }}</span>
                                                <span class="text-gray-300 dark:text-gray-600">•</span>
                                                <span class="text-gray-500 dark:text-gray-400">{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400' : '' }}
                                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400' : '' }}
                                                {{ $order->status === 'canceled' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400' : '' }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                            <span class="text-base font-semibold text-gray-900 dark:text-white">
                                                ${{ number_format($order->total, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Details in this Order -->
                                <div class="px-4 py-3">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100 dark:border-zinc-800' : '' }}">
                                            <div class="flex items-center gap-4">
                                                @if($item->product->photo)
                                                    <img src="{{ asset('storage/products/' . $item->product->photo) }}" 
                                                         alt="{{ $item->product->name }}"
                                                         class="h-12 w-12 rounded-lg object-cover">
                                                @else
                                                    <div class="h-12 w-12 rounded-lg bg-gray-100 dark:bg-zinc-800 flex items-center justify-center">
                                                        <x-lucide-image class="w-6 h-6 text-gray-400" />
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->product->name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        Qty: {{ $item->quantity }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-8">
                                                <div class="text-right">
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">Unit Price</div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">${{ number_format($item->unit_price, 2) }}</div>
                                                </div>
                                                @if($item->discount > 0)
                                                    <div class="text-right">
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">Discount</div>
                                                        <div class="text-sm font-medium text-green-600 dark:text-green-400">-${{ number_format($item->discount, 2) }}</div>
                                                    </div>
                                                @endif
                                                <div class="text-right min-w-[100px]">
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">Subtotal</div>
                                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($item->subtotal, 2) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Order Delivery Address -->
                                    @if($order->delivery_address)
                                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-zinc-800">
                                            <div class="text-sm">
                                                <span class="text-gray-500 dark:text-gray-400">Delivery Address:</span>
                                                <span class="text-gray-900 dark:text-white ml-2">{{ $order->delivery_address }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800">
                            <x-lucide-shopping-bag class="h-8 w-8 text-gray-400" />
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No orders found</h3>
                        <p class="mt-2 text-gray-500 dark:text-gray-400">This product hasn't been ordered yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> 