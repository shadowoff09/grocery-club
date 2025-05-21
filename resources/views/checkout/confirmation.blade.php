<x-layouts.app.header :title="__('Order Confirmation')">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-lg mx-auto bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-8">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full mb-4">
                    <flux:icon name="check" class="w-8 h-8 text-emerald-600 dark:text-emerald-400" />
                </div>

                <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mb-2">
                    {{ __('Order Confirmed!') }}
                </h1>

                <p class="text-zinc-600 dark:text-zinc-400">
                    {{ __('Your order has been successfully placed.') }}
                </p>
            </div>

            @if(isset($order))
                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6 mt-6">
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4">
                        {{ __('Order Details') }}
                    </h2>

                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-zinc-600 dark:text-zinc-400">{{ __('Order Number') }}:</span>
                            <span class="font-medium text-zinc-900 dark:text-zinc-100">#{{ $order->id }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-zinc-600 dark:text-zinc-400">{{ __('Date') }}:</span>
                            <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $order->date }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-zinc-600 dark:text-zinc-400">{{ __('Status') }}:</span>
                            <span class="font-medium px-2 py-1 rounded-full text-xs
                            @if($order->status == 'pending') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                            @elseif($order->status == 'completed') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                            @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 @endif
                        ">
                            {{ ucfirst($order->status) }}
                        </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-zinc-600 dark:text-zinc-400">{{ __('Shipping Address') }}:</span>
                            <span
                                class="font-medium text-zinc-900 dark:text-zinc-100">{{ $order->delivery_address }}</span>
                        </div>
                    </div>

                    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4 mb-4">
                        <h3 class="font-medium text-zinc-900 dark:text-zinc-100 mb-3">
                            {{ __('Order Summary') }}
                        </h3>

                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between">
                                <span class="text-zinc-600 dark:text-zinc-400">{{ __('Items Subtotal') }}:</span>
                                <span
                                    class="text-zinc-900 dark:text-zinc-100">€{{ number_format($order->total_items, 2) }}</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-zinc-600 dark:text-zinc-400">{{ __('Shipping Cost') }}:</span>
                                <span
                                    class="text-zinc-900 dark:text-zinc-100">€{{ number_format($order->shipping_cost, 2) }}</span>
                            </div>

                            <div
                                class="flex justify-between font-semibold border-t border-zinc-200 dark:border-zinc-700 pt-2 mt-2">
                                <span class="text-zinc-900 dark:text-zinc-100">{{ __('Total') }}:</span>
                                <span
                                    class="text-zinc-900 dark:text-zinc-100">€{{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6 mt-6">
                <p class="text-zinc-600 dark:text-zinc-400 mb-6 text-center">
                    {{ __('A confirmation has been sent to your email.') }}
                </p>

                <div class="flex justify-center">
                    <a href="{{ route('orders.index') }}"
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium
                       transition-colors rounded-lg">
                        {{ __('Return to Orders') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app.header>
