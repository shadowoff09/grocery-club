<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    @if(Auth::user()->isPendingMember())
        <flux:callout icon="clock" color="yellow" inline>
            <flux:callout.heading>Account Incomplete</flux:callout.heading>
            <flux:callout.text>
                Your account is currently in a <strong>pending</strong> state.
                <br>
                To access all features, you need to pay your membership fee.
            </flux:callout.text>
            <x-slot name="actions" class="@md:h-full m-0!">
                <a href="{{ route('membership.pending') }}">
                    <flux:button class="cursor-pointer">Pay Membership Fee -></flux:button>
                </a>
            </x-slot>
        </flux:callout>
    @endif
    
    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4">Order Summary</h3>
            @if($orderCount > 0)
                <div class="flex flex-col gap-3">
                    <div class="flex items-center justify-between border-b border-neutral-100 dark:border-neutral-800 pb-3">
                        <span class="text-sm text-neutral-600 dark:text-neutral-400">Total Orders</span>
                        <span class="font-medium text-neutral-900 dark:text-white">{{ $orderCount }}</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-neutral-100 dark:border-neutral-800 pb-3">
                        <span class="text-sm text-neutral-600 dark:text-neutral-400">Recent Order</span>
                        <span class="font-medium text-neutral-900 dark:text-white">
                            @if($recentOrder)
                                #{{ $recentOrder->id }}
                                <span class="text-sm text-neutral-500 dark:text-neutral-400">({{ $recentOrder->created_at->format('M d, Y') }})</span>
                            @else
                                None
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-600 dark:text-neutral-400">Total Spent</span>
                        <span class="font-medium text-neutral-900 dark:text-white">{{ number_format($totalSpent, 2) }} €</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-neutral-100 dark:border-neutral-800">
                    <a href="{{ route('orders.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                        View all orders
                        <flux:icon name="chevron-right" class="w-4 h-4 ml-1" />
                    </a>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-6">
                    <svg class="w-12 h-12 text-neutral-400 dark:text-neutral-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">You haven't placed any orders yet.</p>
                </div>
            @endif
        </div>
        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
    
    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
</div> 