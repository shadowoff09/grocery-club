<div class="flex flex-col gap-8 p-6 max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-3">
        <flux:icon name="banknotes" class="w-8 h-8 text-indigo-600 dark:text-indigo-400" />
        {{ __('Your Balance') }}
    </h1>

    <!-- Card Balance Summary -->
    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/30 dark:to-indigo-800/20 rounded-2xl shadow-lg border border-indigo-200/50 dark:border-indigo-700 p-8 transition-all duration-200 hover:shadow-xl">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-3 w-full md:w-auto">
                <div class="flex items-center gap-3 mb-1">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-indigo-200 dark:bg-indigo-800">
                        <flux:icon name="banknotes" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                    </span>
                    <h2 class="text-lg font-semibold text-zinc-700 dark:text-zinc-300">{{ __('Current Balance') }}</h2>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">
                    {{ number_format($cardBalance, 2) }} €
                    </span>
                    <span class="text-sm font-medium bg-white dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 px-3 py-1.5 rounded-md shadow-sm border border-zinc-200 dark:border-zinc-700 flex items-center gap-2">
                        <flux:icon name="credit-card" class="w-4 h-4 text-indigo-500 dark:text-indigo-400" />
                        Card #{{ $cardNumber }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                <flux:button icon="plus-circle" variant="primary" wire:click="showRechargeForm" class="cursor-pointer shadow-lg hover:shadow-indigo-500/20 transition-all duration-200">
                    {{ __('Recharge Card') }}
                </flux:button>
            </div>
        </div>
    </div>

    @if($statistics)
    <!-- Card Statistics -->
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-lg border border-zinc-200/50 dark:border-zinc-700 p-6 transition-all duration-200 hover:shadow-xl">
        <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100 mb-6 flex items-center gap-2">
            <flux:icon name="chart-bar" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
            {{ __('Card Statistics') }}
        </h2>

        <!-- Basic Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
            <div class="p-5 bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/30 dark:to-emerald-800/20 rounded-xl border border-emerald-100 dark:border-emerald-800/50 shadow-sm hover:shadow transition-all duration-200 flex flex-col justify-between h-full">
                <div class="mb-2">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-200 dark:bg-emerald-800 mb-2">
                        <flux:icon name="arrow-up-circle" class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                    </span>
                    <h3 class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Total Credits</h3>
                </div>
                <div>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($statistics['total_credits'], 2) }} €</p>
                    <div class="flex items-center mt-1">
                        <span class="text-xs text-emerald-600 dark:text-emerald-500">{{ $statistics['credit_count'] }} transactions</span>
                        <span class="ml-auto text-xs bg-emerald-200 dark:bg-emerald-800/70 text-emerald-700 dark:text-emerald-300 px-2 py-0.5 rounded-full">
                            {{ $statistics['credit_count'] > 0 ? number_format($statistics['total_credits'] / $statistics['credit_count'], 2) . ' € avg' : '0.00 € avg' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-5 bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-800/20 rounded-xl border border-red-100 dark:border-red-800/50 shadow-sm hover:shadow transition-all duration-200 flex flex-col justify-between h-full">
                <div class="mb-2">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-200 dark:bg-red-800 mb-2">
                        <flux:icon name="arrow-down-circle" class="w-4 h-4 text-red-600 dark:text-red-400" />
                    </span>
                    <h3 class="text-sm font-medium text-red-700 dark:text-red-300">Total Debits</h3>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($statistics['total_debits'], 2) }} €</p>
                    <div class="flex items-center mt-1">
                        <span class="text-xs text-red-600 dark:text-red-500">{{ $statistics['debit_count'] }} transactions</span>
                        <span class="ml-auto text-xs bg-red-200 dark:bg-red-800/70 text-red-700 dark:text-red-300 px-2 py-0.5 rounded-full">
                            {{ $statistics['debit_count'] > 0 ? number_format($statistics['total_debits'] / $statistics['debit_count'], 2) . ' € avg' : '0.00 € avg' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Statistics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Average Transaction Section -->
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900/30 dark:to-slate-800/20 rounded-xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700">
                        <flux:icon name="calculator" class="w-3 h-3 text-slate-700 dark:text-slate-300" />
                    </span>
                    Average Transactions
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white dark:bg-zinc-800/80 rounded-lg p-3 border border-slate-100 dark:border-zinc-700">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-xs font-medium text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                <flux:icon name="arrow-up" class="w-3 h-3" />
                                Avg. Credit
                            </h4>
                            <span class="text-xs bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 px-1.5 py-0.5 rounded">Credit</span>
                        </div>
                        <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($statistics['avg_credit'], 2) }} €</p>
                    </div>
                    <div class="bg-white dark:bg-zinc-800/80 rounded-lg p-3 border border-slate-100 dark:border-zinc-700">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-xs font-medium text-red-600 dark:text-red-400 flex items-center gap-1">
                                <flux:icon name="arrow-down" class="w-3 h-3" />
                                Avg. Debit
                            </h4>
                            <span class="text-xs bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 px-1.5 py-0.5 rounded">Debit</span>
                        </div>
                        <p class="text-lg font-bold text-red-600 dark:text-red-400">{{ number_format($statistics['avg_debit'], 2) }} €</p>
                    </div>
                </div>
            </div>

            <!-- Largest Transactions Section -->
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900/30 dark:to-slate-800/20 rounded-xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700">
                        <flux:icon name="trophy" class="w-3 h-3 text-slate-700 dark:text-slate-300" />
                    </span>
                    Largest Transactions
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    @if($statistics['largest_credit'])
                    <div class="bg-white dark:bg-zinc-800/80 rounded-lg p-3 border border-slate-100 dark:border-zinc-700">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-xs font-medium text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                <flux:icon name="arrow-trending-up" class="w-3 h-3" />
                                Largest Credit
                            </h4>
                        </div>
                        <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
                            {{ number_format($statistics['largest_credit']['amount'], 2) }} €
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1 flex items-center gap-1">
                            <flux:icon name="calendar" class="w-3 h-3" />
                            {{ \Carbon\Carbon::parse($statistics['largest_credit']['date'])->format('M d, Y') }}
                        </p>
                    </div>
                    @endif
                    @if($statistics['largest_debit'])
                    <div class="bg-white dark:bg-zinc-800/80 rounded-lg p-3 border border-slate-100 dark:border-zinc-700">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-xs font-medium text-red-600 dark:text-red-400 flex items-center gap-1">
                                <flux:icon name="arrow-trending-down" class="w-3 h-3" />
                                Largest Debit
                            </h4>
                        </div>
                        <p class="text-lg font-bold text-red-600 dark:text-red-400">
                            {{ number_format($statistics['largest_debit']['amount'], 2) }} €
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1 flex items-center gap-1">
                            <flux:icon name="calendar" class="w-3 h-3" />
                            {{ \Carbon\Carbon::parse($statistics['largest_debit']['date'])->format('M d, Y') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Monthly Activity Section -->
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900/30 dark:to-slate-800/20 rounded-xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm lg:col-span-2">
                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700">
                        <flux:icon name="calendar-days" class="w-3 h-3 text-slate-700 dark:text-slate-300" />
                    </span>
                    Monthly Activity (Last 3 Months)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($statistics['monthly_activity'] as $index => $monthData)
                    <div class="bg-white dark:bg-zinc-800/80 rounded-lg border border-slate-100 dark:border-zinc-700 p-4 relative overflow-hidden {{ $index === 0 ? 'ring-2 ring-indigo-500 dark:ring-indigo-400 ring-opacity-20' : '' }}">
                        @if($index === 0)
                        <span class="absolute top-0 right-0 bg-indigo-500 text-white text-xs px-2 py-0.5 rounded-bl-md">Current</span>
                        @endif
                        <h4 class="text-xs font-medium text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-1">
                            <flux:icon name="calendar" class="w-3 h-3 text-slate-500 dark:text-slate-400" />
                            {{ $monthData['month'] }}
                        </h4>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Credits</p>
                                    <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ number_format($monthData['credits'], 2) }} €
                                    </p>
                                </div>
                                <div class="h-8 w-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                    <flux:icon name="arrow-up-circle" class="w-4 h-4 text-emerald-500 dark:text-emerald-400" />
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-xs text-red-600 dark:text-red-400 font-medium">Debits</p>
                                    <p class="text-sm font-bold text-red-600 dark:text-red-400">
                                        {{ number_format($monthData['debits'], 2) }} €
                                    </p>
                                </div>
                                <div class="h-8 w-8 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                    <flux:icon name="arrow-down-circle" class="w-4 h-4 text-red-500 dark:text-red-400" />
                                </div>
                            </div>
                            
                            <div class="pt-2 border-t border-slate-100 dark:border-zinc-700">
                                <div class="flex justify-between items-center">
                                    <p class="text-xs text-slate-600 dark:text-slate-400 font-medium">Net Balance</p>
                                    <p class="text-sm font-bold {{ ($monthData['credits'] - $monthData['debits']) >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ ($monthData['credits'] - $monthData['debits']) >= 0 ? '+' : '' }}{{ number_format($monthData['credits'] - $monthData['debits'], 2) }} €
                                    </p>
                                </div>
                                
                                @php
                                    $netPercentage = 0;
                                    $isPositive = ($monthData['credits'] - $monthData['debits']) >= 0;
                                    
                                    if ($monthData['credits'] > 0 || $monthData['debits'] > 0) {
                                        $total = $monthData['credits'] + $monthData['debits'];
                                        $netPercentage = min(100, max(0, $isPositive ? 
                                            ($monthData['credits'] / $total) * 100 : 
                                            ($monthData['debits'] / $total) * 100));
                                    }
                                @endphp
                                
                                <div class="w-full bg-slate-100 dark:bg-zinc-700 rounded-full h-1.5 mt-2 overflow-hidden">
                                    <div class="{{ $isPositive ? 'bg-emerald-500 dark:bg-emerald-400' : 'bg-red-500 dark:bg-red-400' }}" 
                                         style="width: {{ $netPercentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
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
                        <flux:button variant="outline" wire:click="useDefaults" class="cursor-pointer">
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

                @if($paymentMethod !== $defaultPaymentMethod || $paymentReference !== $defaultPaymentReference)
                <div class="flex items-center gap-2 mt-2">
                    <flux:checkbox
                        id="saveAsDefault"
                        wire:model="saveAsDefault"
                        label="Save as default payment method"
                    />
                </div>
                @endif

                <div class="flex justify-end gap-3 mt-2">
                    <flux:button variant="outline" wire:click="cancelRecharge" type="button" class="cursor-pointer">Cancel</flux:button>
                    <flux:button variant="primary" type="submit" wire:loading.attr="disabled" class="cursor-pointer">
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
