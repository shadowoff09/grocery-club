<div class="flex flex-col gap-8 p-6 max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-3">
        <flux:icon name="banknotes" class="w-8 h-8 text-indigo-600 dark:text-indigo-400" />
        {{ __('Your Balance') }}
    </h1>

    <!-- Card Balance Summary -->
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-lg border border-zinc-200/50 dark:border-zinc-700 p-6 transition-all duration-200 hover:shadow-xl">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-2 w-full md:w-auto">
                <h2 class="text-lg font-semibold text-zinc-700 dark:text-zinc-300">{{ __('Current Balance') }}</h2>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                    {{ number_format($cardBalance, 2) }} €
                    </span>
                    <span class="text-sm font-normal text-zinc-500 dark:text-zinc-500 bg-zinc-100 dark:bg-zinc-700/50 px-2 py-1 rounded-md">
                        Card #{{ $cardNumber }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                <flux:button icon="plus-circle" variant="primary" wire:click="showRechargeForm" class="cursor-pointer">
                    {{ __('Recharge Card') }}
                </flux:button>
            </div>
        </div>
    </div>

    @if($statistics)
    <!-- Card Statistics -->
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-lg border border-zinc-200/50 dark:border-zinc-700 p-6 transition-all duration-200 hover:shadow-xl">
        <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
            <flux:icon name="chart-bar" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
            {{ __('Card Statistics') }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-100 dark:border-emerald-800/50">
                <h3 class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Total Credits</h3>
                <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($statistics['total_credits'], 2) }} €</p>
            </div>

            <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-100 dark:border-red-800/50">
                <h3 class="text-sm font-medium text-red-700 dark:text-red-300">Total Debits</h3>
                <p class="text-xl font-bold text-red-600 dark:text-red-400">{{ number_format($statistics['total_debits'], 2) }} €</p>
            </div>

            <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border border-indigo-100 dark:border-indigo-800/50">
                <h3 class="text-sm font-medium text-indigo-700 dark:text-indigo-300">Transactions</h3>
                <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">{{ $statistics['total_transactions'] }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Recharge Modal -->
    @if($showRechargeModal)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl border border-zinc-200/50 dark:border-zinc-700 w-full max-w-md p-6 animate-fade-in">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-2">
                    <flux:icon name="credit-card" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                    {{ __('Recharge Your Card') }}
                </h3>
                <button wire:click="cancelRecharge" class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200">
                    <flux:icon name="x-mark" class="w-5 h-5" />
                </button>
            </div>

            <form wire:submit="rechargeCard" class="flex flex-col gap-4">
                <div>
                    <p class="text-sm dark:text-gray-300 text-gray-600">
                        Please select your payment method and enter the required details.
                    </p>
                </div>

                @if($showDefaultsAlert)
                <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/40 rounded-lg p-3 mb-2">
                    <div class="flex flex-col gap-2">
                        <p class="text-sm text-indigo-700 dark:text-indigo-300">
                            You have saved payment preferences:
                            <span class="font-medium">{{ $defaultPaymentMethod }}</span>
                            @if($defaultPaymentMethod === 'Visa')
                                ending in {{ substr($defaultPaymentReference, -4) }}
                            @elseif($defaultPaymentMethod === 'PayPal')
                                ({{ $defaultPaymentReference }})
                            @elseif($defaultPaymentMethod === 'MB WAY')
                                ({{ $defaultPaymentReference }})
                            @endif
                        </p>
                        <flux:button variant="outline" wire:click="useDefaults">
                            Use saved payment method
                        </flux:button>
                    </div>
                </div>
                @endif

                <div class="flex flex-col gap-1.5">
                    <flux:input
                        id="rechargeAmount"
                        wire:model="rechargeAmount"
                        type="number"
                        label="Amount (€)"
                        min="5"
                        max="1000"
                        step="5"
                        prefix="€"
                    />

                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 mt-2">
                        <flux:button type="button" size="xs" variant="outline" class="w-full" wire:click="$set('rechargeAmount', 20)">20€</flux:button>
                        <flux:button type="button" size="xs" variant="outline" class="w-full" wire:click="$set('rechargeAmount', 50)">50€</flux:button>
                        <flux:button type="button" size="xs" variant="outline" class="w-full" wire:click="$set('rechargeAmount', 100)">100€</flux:button>
                        <flux:button type="button" size="xs" variant="outline" class="w-full" wire:click="$set('rechargeAmount', 200)">200€</flux:button>
                    </div>
                </div>

                <div class="flex flex-col gap-1.5">
                    <flux:select
                        id="paymentMethod"
                        wire:model.live="paymentMethod"
                        label="Payment Method"
                    >
                        <option value="Visa">Visa</option>
                        <option value="PayPal">PayPal</option>
                        <option value="MB WAY">MB WAY</option>
                    </flux:select>
                </div>

                <div class="flex flex-col gap-1.5">
                    <flux:input
                        id="paymentReference"
                        wire:model="paymentReference"
                        type="text"
                        :label="$paymentMethod === 'Visa' ? 'Card Number' :
                               ($paymentMethod === 'PayPal' ? 'Email' :
                               ($paymentMethod === 'MB WAY' ? 'Phone Number' : 'Reference'))"
                        placeholder="{{ $this->getPlaceholderForPaymentType($paymentMethod) }}"
                    />
                </div>

                @if($paymentMethod === 'Visa')
                <div class="flex flex-col gap-1.5">
                    <flux:input
                        id="cvcCode"
                        wire:model="cvcCode"
                        type="text"
                        label="CVC Code"
                        placeholder="3-digit CVC code (cannot start with 0 or end with 2)"
                        maxlength="3"
                    />
                </div>
                @endif

                @if($hasDefaults && ($paymentMethod !== $defaultPaymentMethod || $paymentReference !== $defaultPaymentReference))
                <div class="flex items-center gap-2 mt-2">
                    <flux:checkbox
                        id="saveAsDefault"
                        wire:model="saveAsDefault"
                        label="Save as default payment method"
                    />
                </div>
                @endif

                <div class="flex justify-end gap-3 mt-2">
                    <flux:button variant="outline" wire:click="cancelRecharge" type="button">Cancel</flux:button>
                    <flux:button variant="primary" type="submit" wire:loading.attr="disabled">
                        <span wire:loading.remove>Recharge Card</span>
                        <span wire:loading>Processing...</span>
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Transaction History -->
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-lg border border-zinc-200/50 dark:border-zinc-700 p-6 transition-all duration-200 hover:shadow-xl">
        <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100 mb-4 flex items-center gap-2">
            <flux:icon name="clock" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
            {{ __('Recent Transactions') }}
        </h2>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($operations as $operation)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-700 dark:text-zinc-300">
                            {{ \Carbon\Carbon::parse($operation->created_at)->format('d M Y') }}
                            <div class="text-xs text-zinc-500 dark:text-zinc-500">
                                {{ \Carbon\Carbon::parse($operation->created_at)->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($operation->type === 'credit')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-800/30 text-green-800 dark:text-green-300">
                                    Credit
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-800/30 text-red-800 dark:text-red-300">
                                    Debit
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-700 dark:text-zinc-300">
                            @if($operation->debit_type === 'membership_fee')
                                Membership Fee
                            @elseif($operation->debit_type === 'order')
                                Order #{{ $operation->order_id }}
                            @elseif($operation->credit_type === 'payment')
                                Payment ({{ $operation->payment_type }})
                            @elseif($operation->credit_type === 'order_cancellation')
                                Order Cancellation
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $operation->type === 'credit' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $operation->type === 'credit' ? '+' : '-' }}{{ number_format($operation->value, 2) }} €
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            No transactions yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $operations->links() }}
        </div>
    </div>
</div>
