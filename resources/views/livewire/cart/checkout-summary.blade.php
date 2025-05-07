<div class="space-y-4 text-sm">
    <div class="flex justify-between">
        <span class="text-zinc-600 dark:text-zinc-400">Subtotal</span>
        <span class="font-medium text-zinc-900 dark:text-zinc-100">
            ${{ number_format($total + $totalDiscount, 2) }}
        </span>
    </div>

    @if($totalDiscount > 0)
    <div class="flex justify-between">
        <span class="text-zinc-600 dark:text-zinc-400">Quantity Discounts</span>
        <span class="font-medium text-emerald-600 dark:text-emerald-400">
            -${{ number_format($totalDiscount, 2) }}
        </span>
    </div>
    @endif

    <div class="flex flex-col gap-2">
        <div class="flex justify-between">
            <span class="text-zinc-600 dark:text-zinc-400">Shipping</span>
            @if($shippingCost == 0)
                <span class="font-medium text-emerald-600 dark:text-emerald-400">
                    Free
                </span>
            @else
                <span class="font-medium text-zinc-900 dark:text-zinc-100">
                    ${{ number_format($shippingCost, 2) }}
                </span>
            @endif
        </div>
    </div>

    <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <span class="font-semibold text-zinc-800 dark:text-zinc-200">Total Amount</span>
            <div class="flex flex-col items-end gap-1">
                @if($shippingCost == 0)
                    <span class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">
                        Free shipping included!
                    </span>
                @endif
                <span class="text-2xl font-bold tracking-tight text-indigo-600 dark:text-indigo-400">
                    ${{ number_format($totalWithShipping, 2) }}
                </span>
            </div>
        </div>
    </div>
</div> 