<div>
    <div class="mx-auto py-8">

        <!-- Actions -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <flux:icon name="cog-6-tooth" class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                Inventory Actions
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <flux:button icon="plus-circle" variant="primary" class="cursor-pointer w-full"
                    href="{{ route('employee.inventory.restock') }}">
                    Request Restock
                </flux:button>

                <flux:button icon="clipboard-document-list" variant="outline" class="cursor-pointer w-full"
                    href="{{ route('employee.inventory.supply-orders') }}">
                    Manage Supply Orders
                </flux:button>

                <flux:button icon="list-bullet" variant="outline" class="cursor-pointer w-full"
                    href="{{ route('board.catalog.products') }}">
                    View All Products
                </flux:button>
            </div>
        </div>

        <!-- Stock Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- No Stock -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                        <flux:icon name="exclamation-triangle" class="w-5 h-5 text-red-600 dark:text-red-400" />
                    </span>
                    <h3 class="text-lg font-semibold text-red-600 dark:text-red-400">No Stock</h3>
                </div>
                <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $noStockProducts }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Products out of stock</p>
            </div>

            <!-- Low Stock -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
                <div class="flex items-center gap-3 mb-3">
                    <span
                        class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                        <flux:icon name="exclamation-circle" class="w-5 h-5 text-yellow-600 dark:text-yellow-400" />
                    </span>
                    <h3 class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">Low Stock</h3>
                </div>
                <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $lowStockProducts }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Products with 1-5 units</p>
            </div>

            <!-- Healthy Stock -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
                <div class="flex items-center gap-3 mb-3">
                    <span
                        class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center">
                        <flux:icon name="check-circle" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                    </span>
                    <h3 class="text-lg font-semibold text-emerald-600 dark:text-emerald-400">Healthy Stock</h3>
                </div>
                <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $healthyStockProducts }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Products with 6+ units</p>
            </div>

            <!-- Pending Supply Orders -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
                <div class="flex items-center gap-3 mb-3">
                    <span
                        class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                        <flux:icon name="clock" class="w-5 h-5 text-yellow-600 dark:text-yellow-400" />
                    </span>
                    <h3 class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">Pending Supply Orders</h3>
                </div>
                <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingSupplyOrders }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pending supply orders</p>
            </div>
        </div>

        <!-- Stats -->
        <div
            class="mb-8 bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <flux:icon name="chart-bar" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                Inventory Statistics
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg">
                    <flux:icon name="currency-dollar"
                        class="w-8 h-8 mx-auto text-indigo-600 dark:text-indigo-400 mb-2" />
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                        ${{ number_format($inventoryStats['totalValue'], 2) }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Inventory Value</p>
                </div>
                <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                    <flux:icon name="chart-pie" class="w-8 h-8 mx-auto text-purple-600 dark:text-purple-400 mb-2" />
                    <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $inventoryStats['avgStock'] }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Average Stock per Product</p>
                </div>
                <div class="text-center p-4 bg-teal-50 dark:bg-teal-900/30 rounded-lg">
                    <flux:icon name="squares-2x2" class="w-8 h-8 mx-auto text-teal-600 dark:text-teal-400 mb-2" />
                    <p class="text-2xl font-bold text-teal-600 dark:text-teal-400">
                        {{ $inventoryStats['categoriesWithStock'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Categories with Stock</p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Alerts -->
            @if (count($lowStockAlerts) > 0)
                <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800">
                    <div
                        class="px-6 py-4 border-b rounded-t-xl border-zinc-200 dark:border-zinc-800 bg-yellow-50 dark:bg-yellow-900/20">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <flux:icon name="exclamation-triangle" class="w-5 h-5 text-yellow-500" />
                            Low Stock Alerts
                            <span
                                class="ml-auto bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                {{ count($lowStockAlerts) }} products
                            </span>
                        </h3>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        @foreach ($lowStockAlerts as $product)
                            <div
                                class="p-4 border-b border-zinc-200 dark:border-zinc-800 last:border-b-0 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}
                                        </h4>
                                        <div
                                            class="flex items-center gap-2 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            <flux:icon name="tag" class="w-3 h-3" />
                                            {{ $product->category ? $product->category->name : 'No Category' }}
                                            <span>•</span>
                                            <span>${{ number_format($product->price, 2) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        @if ($product->stock <= 2)
                                            <span
                                                class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-3 py-1 rounded-full text-sm font-medium">
                                                {{ $product->stock }} left
                                            </span>
                                        @else
                                            <span
                                                class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 px-3 py-1 rounded-full text-sm font-medium">
                                                {{ $product->stock }} left
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Pending Supply Orders -->
            @if (count($this->pendingSupplyOrders) > 0)
                <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800">
                    <div
                        class="px-6 py-4 border-b rounded-t-xl border-zinc-200 dark:border-zinc-800 bg-blue-50 dark:bg-blue-900/20">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <flux:icon name="clock" class="w-5 h-5 text-blue-500" />
                            Pending Restock Requests
                            <span
                                class="ml-auto bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                {{ count($this->pendingSupplyOrders) }} pending
                            </span>
                            <flux:button icon="arrow-right" variant="outline" size="sm" class="cursor-pointer"
                                href="{{ route('employee.inventory.supply-orders') }}">
                                View All
                            </flux:button>
                        </h3>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        @foreach ($this->pendingSupplyOrders as $order)
                            <div
                                class="p-4 border-b border-zinc-200 dark:border-zinc-800 last:border-b-0 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">
                                            {{ $order->product->name }}</h4>
                                        <div
                                            class="flex items-center gap-2 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            <flux:icon name="user" class="w-3 h-3" />
                                            {{ $order->registeredBy->name }}
                                            <span>•</span>
                                            <flux:icon name="calendar" class="w-3 h-3" />
                                            {{ $order->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                            {{ $order->quantity }} units
                                        </span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Requested
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Top Selling -->
                <div
                    class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800">
                    <div
                        class="px-6 py-4 border-b rounded-t-xl border-zinc-200 dark:border-zinc-800 bg-emerald-50 dark:bg-emerald-900/20">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <flux:icon name="fire" class="w-5 h-5 text-emerald-500" />
                            Top Selling Products
                        </h3>
                    </div>
                    <div>
                        @if (count($topSellingProducts) > 0)
                            @foreach ($topSellingProducts as $index => $product)
                                <div
                                    class="p-4 border-b border-zinc-200 dark:border-zinc-800 last:border-b-0 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                                {{ $product->name }}</h4>
                                            <div
                                                class="flex items-center gap-2 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                <flux:icon name="tag" class="w-3 h-3" />
                                                {{ $product->category ? $product->category->name : 'No Category' }}
                                                <span>•</span>
                                                <flux:icon name="cube" class="w-3 h-3" />
                                                {{ $product->stock }} in stock
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-sm font-medium text-emerald-600 dark:text-emerald-400">
                                                {{ $product->item_orders_count }} sold
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                                <flux:icon name="chart-bar" class="w-12 h-12 mx-auto mb-4 text-gray-400" />
                                <p>No sales data available yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Out of Stock -->
            @if (count($recentNoStockProducts) > 0)
                <div
                    class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800">
                    <div
                        class="px-6 py-4 border-b rounded-t-xl border-zinc-200 dark:border-zinc-800 bg-red-50 dark:bg-red-900/20">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <flux:icon name="x-circle" class="w-5 h-5 text-red-500" />
                            Recently Out of Stock
                        </h3>
                    </div>
                    <div>
                        @foreach ($recentNoStockProducts as $product)
                            <div
                                class="p-4 border-b border-zinc-200 dark:border-zinc-800 last:border-b-0 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}
                                        </h4>
                                        <div
                                            class="flex items-center gap-2 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            <flux:icon name="tag" class="w-3 h-3" />
                                            {{ $product->category ? $product->category->name : 'No Category' }}
                                            <span>•</span>
                                            <span>${{ number_format($product->price, 2) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span
                                            class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                            Out of Stock
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- New Products -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800">
                <div
                    class="px-6 py-4 border-b rounded-t-xl border-zinc-200 dark:border-zinc-800 bg-blue-50 dark:bg-blue-900/20">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <flux:icon name="plus-circle" class="w-5 h-5 text-blue-500" />
                        Recently Added Products
                    </h3>
                </div>
                <div>
                    @if (count($recentlyAddedProducts) > 0)
                        @foreach ($recentlyAddedProducts as $product)
                            <div
                                class="p-4 border-b border-zinc-200 dark:border-zinc-800 last:border-b-0 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900 dark:text-white">{{ $product->name }}
                                        </h4>
                                        <div
                                            class="flex items-center gap-2 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            <flux:icon name="tag" class="w-3 h-3" />
                                            {{ $product->category ? $product->category->name : 'No Category' }}
                                            <span>•</span>
                                            <flux:icon name="calendar" class="w-3 h-3" />
                                            {{ $product->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $product->stock }} units
                                        </span>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            ${{ number_format($product->price, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                            <flux:icon name="cube" class="w-12 h-12 mx-auto mb-4 text-gray-400" />
                            <p>No recent products added</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stock Adjustments -->
        @if (count($recentStockAdjustments) > 0)
            <div class="mb-8">
                <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800">
                    <div class="px-6 py-4 border-b rounded-t-xl border-zinc-200 dark:border-zinc-800 bg-purple-50 dark:bg-purple-900/20">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <flux:icon name="adjustments-horizontal" class="w-5 h-5 text-purple-500" />
                            Recent Stock Adjustments
                            <span class="ml-auto bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                {{ count($recentStockAdjustments) }} recent
                            </span>
                        </h3>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        @foreach ($recentStockAdjustments as $adjustment)
                            <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 last:border-b-0 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                                {{ $adjustment->product->name }}
                                            </h4>
                                            @if ($adjustment->quantity_changed > 0)
                                                <span class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-0.5 rounded-full text-xs font-medium">
                                                    +{{ $adjustment->quantity_changed }}
                                                </span>
                                            @else
                                                <span class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-0.5 rounded-full text-xs font-medium">
                                                    {{ $adjustment->quantity_changed }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            <flux:icon name="tag" class="w-3 h-3" />
                                            {{ $adjustment->product->category ? $adjustment->product->category->name : 'No Category' }}
                                            <span>•</span>
                                            <flux:icon name="user" class="w-3 h-3" />
                                            {{ $adjustment->registeredBy->name }}
                                            <span>•</span>
                                            <flux:icon name="calendar" class="w-3 h-3" />
                                            {{ $adjustment->created_at->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            Current: {{ $adjustment->product->stock }}
                                        </span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            units in stock
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Empty State -->
        @if (count($recentNoStockProducts) == 0 && count($recentLowStockProducts) == 0 && count($lowStockAlerts) == 0)
            <div class="mt-8">
                <div
                    class="text-center py-12 bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800">
                    <flux:icon name="check-circle"
                        class="w-12 h-12 mx-auto text-emerald-400 dark:text-emerald-600 mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">All products are well stocked!
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">No products require immediate attention.
                    </p>
                    <flux:button icon="list-bullet" variant="outline" class="cursor-pointer"
                        href="{{ route('board.catalog.products') }}">
                        View All Products
                    </flux:button>
                </div>
            </div>
        @endif
    </div>
</div>
