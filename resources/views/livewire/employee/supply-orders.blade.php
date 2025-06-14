<div class="space-y-8">
    @if (session()->has('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex">
                <flux:icon name="check-circle" class="w-5 h-5 text-green-400" />
                <div class="ml-3">
                    <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex">
                <flux:icon name="x-circle" class="w-5 h-5 text-red-400" />
                <div class="ml-3">
                    <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
            <div class="flex items-center gap-3 mb-3">
                <span class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                    <flux:icon name="clock" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                </span>
                <h3 class="text-lg font-semibold text-blue-600 dark:text-blue-400">Pending Orders</h3>
            </div>
            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['pendingOrders'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Awaiting completion</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
            <div class="flex items-center gap-3 mb-3">
                <span class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                    <flux:icon name="check-circle" class="w-5 h-5 text-green-600 dark:text-green-400" />
                </span>
                <h3 class="text-lg font-semibold text-green-600 dark:text-green-400">Completed Orders</h3>
            </div>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['completedOrders'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Orders processed</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
            <div class="flex items-center gap-3 mb-3">
                <span
                    class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                    <flux:icon name="cube" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                </span>
                <h3 class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">Total Pending Units</h3>
            </div>
            <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                {{ number_format($stats['totalPendingUnits']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Units to be added</p>
        </div>
    </div>

    <!-- Actions & Filters -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Supply Orders</h2>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ count($selectedOrders) }} orders selected
                </span>
                @if (count($selectedOrders) > 0)
                    <flux:button size="sm" variant="outline" wire:click="clearSelection">
                        Clear All
                    </flux:button>
                    <flux:button size="sm" variant="primary" wire:click="completeBatchOrders">
                        Complete Selected ({{ count($selectedOrders) }})
                    </flux:button>
                @endif
            </div>
        </div>

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Search -->
            <div>
                <flux:input wire:model.live.debounce.300ms="search" placeholder="Search by product or employee..."
                    icon="magnifying-glass" label="Search Orders" />
            </div>

            <!-- Status Filter -->
            <div>
                <flux:select wire:model.live="statusFilter" label="Status">
                    <option value="requested">Pending Orders</option>
                    <option value="completed">Completed Orders</option>
                    <option value="all">All Orders</option>
                </flux:select>
            </div>

            <!-- Quick Actions -->
            <div class="flex items-end gap-2">
                <flux:button variant="outline" icon="check-circle" wire:click="selectAllVisible" class="cursor-pointer">
                    Select All Pending
                </flux:button>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-800">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-800">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Orders List</h2>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Showing {{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }}
                    orders
                </span>
            </div>
        </div>

        @if ($orders->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-zinc-700">
                @foreach ($orders as $order)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                @if ($order->status === 'requested')
                                    <input type="checkbox" wire:click="toggleOrder({{ $order->id }})"
                                        @checked(in_array($order->id, $selectedOrders))
                                        class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                @else
                                    <div class="w-5 h-5"></div>
                                @endif

                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">
                                            {{ $order->product->name }}</h3>
                                        @if ($order->status === 'requested')
                                            <span
                                                class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 px-2 py-1 rounded-full text-xs font-medium">
                                                Pending
                                            </span>
                                        @else
                                            <span
                                                class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-1 rounded-full text-xs font-medium">
                                                Completed
                                            </span>
                                        @endif
                                    </div>

                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center gap-1">
                                            <flux:icon name="tag" class="w-4 h-4" />
                                            {{ $order->product->category ? $order->product->category->name : 'No Category' }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <flux:icon name="cube" class="w-4 h-4" />
                                            Quantity: {{ $order->quantity }} units
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <flux:icon name="user" class="w-4 h-4" />
                                            {{ $order->registeredBy->name }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <flux:icon name="calendar" class="w-4 h-4" />
                                            {{ $order->created_at->format('M d, Y H:i') }}
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="mt-2 p-3 bg-gray-50 dark:bg-zinc-800 rounded-lg">
                                        <div class="flex items-center justify-between text-sm">
                                            <div>
                                                <span class="text-gray-600 dark:text-gray-400">Current Stock:</span>
                                                <span
                                                    class="font-medium text-gray-900 dark:text-white ml-1">{{ $order->product->stock }}
                                                    units</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-600 dark:text-gray-400">After completion:</span>
                                                <span class="font-medium text-green-600 dark:text-green-400 ml-1">
                                                    {{ $order->product->stock + ($order->status === 'requested' ? $order->quantity : 0) }}
                                                    units
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-gray-600 dark:text-gray-400">Product Price:</span>
                                                <span
                                                    class="font-medium text-gray-900 dark:text-white ml-1">${{ number_format($order->product->price, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-3">
                                @if ($order->status === 'requested')
                                    <flux:button size="sm" variant="primary" icon="check"
                                        wire:click="openCompleteModal({{ $order->id }})" class="cursor-pointer">
                                        Complete Order
                                    </flux:button>
                                @else
                                    <div class="flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                                        <flux:icon name="check-circle" class="w-4 h-4" />
                                        <span>Completed {{ $order->updated_at->format('M d, Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                <flux:icon name="inbox" class="w-16 h-16 mx-auto mb-4 text-gray-400" />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No supply orders found</h3>
                <p class="mb-4">
                    @if ($statusFilter === 'requested')
                        No pending supply orders at the moment.
                    @elseif($statusFilter === 'completed')
                        No completed supply orders found.
                    @else
                        No supply orders match your search criteria.
                    @endif
                </p>
                @if ($statusFilter === 'requested')
                    <flux:button icon="plus" variant="primary" href="{{ route('employee.inventory.restock') }}"
                        class="cursor-pointer">
                        Create New Restock Request
                    </flux:button>
                @endif
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if ($orders->hasPages())
        <div class="flex justify-center">
            {{ $orders->links() }}
        </div>
    @endif

    <!-- Completion Confirmation Modal -->
    @if ($showCompleteModal && $orderToComplete)
        @php
            $order = $orders->where('id', $orderToComplete)->first();
        @endphp

        @if ($order)
            <flux:modal wire:model="showCompleteModal">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30 sm:mx-0 sm:h-10 sm:w-10">
                        <flux:icon name="check-circle" class="h-6 w-6 text-green-600 dark:text-green-400" />
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Complete Supply Order
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Are you sure you want to complete this supply order? This will add
                                <span class="font-semibold text-green-600 dark:text-green-400">{{ $order->quantity }}
                                    units</span>
                                to <span class="font-semibold">{{ $order->product->name }}</span>.
                            </p>
                            <div class="mt-3 p-3 bg-gray-50 dark:bg-zinc-800 rounded-lg">
                                <div class="text-sm">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-600 dark:text-gray-400">Current Stock:</span>
                                        <span class="font-medium">{{ $order->product->stock }} units</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">After completion:</span>
                                        <span
                                            class="font-medium text-green-600 dark:text-green-400">{{ $order->product->stock + $order->quantity }}
                                            units</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="flex flex-row-reverse gap-3">
                    <flux:button wire:click="completeOrder" variant="primary" class="cursor-pointer">
                        Complete Order
                    </flux:button>

                    <flux:button wire:click="closeCompleteModal" variant="outline" class="cursor-pointer">
                        Cancel
                    </flux:button>
                </div>
            </flux:modal>
        @endif
    @endif
</div>
