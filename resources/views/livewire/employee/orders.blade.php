<div>
    <div class="mx-auto py-8">

        <!-- Status Filter -->
        <div class="mb-6 bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-4">
            <div class="flex flex-wrap gap-2">
            <button wire:click="filterByStatus('pending')"
                   class="cursor-pointer px-4 py-2 text-sm rounded-lg transition-colors {{ $statusFilter === 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200 font-medium' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                   Pending
                </button>
                <button wire:click="filterByStatus('completed')"
                   class="cursor-pointer px-4 py-2 text-sm rounded-lg transition-colors {{ $statusFilter === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200 font-medium' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                   Completed
                </button>
                <button wire:click="filterByStatus('canceled')"
                   class="cursor-pointer px-4 py-2 text-sm rounded-lg transition-colors {{ $statusFilter === 'canceled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 font-medium' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                   Canceled
                </button>
                <button wire:click="filterByStatus('all')"
                   class="cursor-pointer px-4 py-2 text-sm rounded-lg transition-colors {{ $statusFilter === null ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 font-medium' : 'text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                   All Orders
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
                                    Order #{{ $order->id }} - {{ $order->member->email }}
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
                                    <flux:button icon="check-circle" wire:click="markAsCompleted({{ $order->id }})" class="cursor-pointer bg-emerald-100! text-emerald-600! dark:bg-emerald-900/50 dark:text-emerald-300 hover:bg-emerald-200! hover:text-emerald-700! dark:hover:bg-emerald-800/50 dark:hover:text-emerald-200 transition-all duration-200 ease-in-out">
                                        Mark as completed
                                    </flux:button>
                                @endif

                                @if(auth()->user()->type === 'board' && $order->status === 'pending')
                                    <flux:modal.trigger name="confirm-cancel-order-{{ $order->id }}">
                                        <flux:button icon="x-circle" class="cursor-pointer bg-red-100! text-red-600! dark:bg-red-900/50 dark:text-red-300 hover:bg-red-200! hover:text-red-700! dark:hover:bg-red-800/50 dark:hover:text-red-200 transition-all duration-200 ease-in-out">
                                            Cancel Order and Refund
                                        </flux:button>
                                    </flux:modal.trigger>

                                    <flux:modal name="confirm-cancel-order-{{ $order->id }}" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
                                        <form wire:submit.prevent="cancelOrder({{ $order->id }}, '{{ $cancel_reason }}')" class="space-y-6">
                                            <!-- Modal Header -->
                                            <div class="border-b border-zinc-200 dark:border-zinc-700 pb-4">
                                                <flux:heading size="lg" class="text-red-600 dark:text-red-500">
                                                    {{ __('Cancel Order #:id', ['id' => $order->id]) }}
                                                </flux:heading>
                                                <flux:subheading class="mt-2 text-zinc-600 dark:text-zinc-400">
                                                    {{ __('Please confirm that you want to cancel this order. This action will:') }}
                                                </flux:subheading>
                                                <ul class="mt-4 space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                                                    <li class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        {{ __('Cancel the order and mark it as canceled') }}
                                                    </li>
                                                    <li class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                                        </svg>
                                                        {{ __('Process a full refund to the customer') }}
                                                    </li>
                                                    <li class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                        </svg>
                                                        {{ __('Send a notification email to the customer') }}
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- Cancellation Reason -->
                                            <div class="space-y-4">
                                                <div>
                                                    <label for="cancel_reason" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                                        {{ __('Cancellation Reason') }}
                                                        <span class="text-zinc-400 dark:text-zinc-500 text-xs font-normal">({{ __('optional') }})</span>
                                                    </label>
                                                    <flux:input 
                                                        type="text" 
                                                        id="cancel_reason"
                                                        wire:model.live="cancel_reason" 
                                                        placeholder="{{ __('Enter a custom reason for cancellation (optional)') }}"
                                                        class="w-full"
                                                    />
                                                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                                        {{ __('If no reason is provided, it will default to "Order cancelled by a board member".') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="flex justify-end space-x-3 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                                                <flux:modal.close>
                                                    <flux:button 
                                                        variant="outline" 
                                                        class="cursor-pointer"
                                                    >
                                                        {{ __('Keep Order') }}
                                                    </flux:button>
                                                </flux:modal.close>

                                                <flux:button 
                                                    variant="danger" 
                                                    type="submit" 
                                                    class="cursor-pointer"
                                                    icon="x-circle"
                                                >
                                                    {{ __('Confirm Cancellation') }}
                                                </flux:button>
                                            </div>
                                        </form>
                                    </flux:modal>
                                @endif

                                @if($order->pdf_receipt && $order->status === 'completed')
                                        <a href="{{ route('receipts.show', $order->id) }}"
                                       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors">
                                        <x-lucide-file-text class="w-4 h-4 mr-2" />
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
                    @if($statusFilter === 'pending')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif($statusFilter === 'completed')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    @endif
                </div>

                <h3 class="mt-6 text-xl font-medium text-zinc-900 dark:text-zinc-100">
                    No {{ $statusFilter ? $statusFilter . ' ' : '' }}orders found
                </h3>
                <p class="mt-2 text-zinc-600 dark:text-zinc-400">
                    There are no {{ $statusFilter ? $statusFilter . ' ' : '' }} orders found.
                </p>
            </div>
        @endif
    </div>
</div>
