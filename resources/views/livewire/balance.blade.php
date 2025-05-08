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
                        {{ number_format($card->balance, 2) }} €
                    </span>
                    <span class="text-sm font-normal text-zinc-500 dark:text-zinc-500 bg-zinc-100 dark:bg-zinc-700/50 px-2 py-1 rounded-md">
                        Card #{{ $card->card_number }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-full p-4 shadow-md hidden md:flex items-center justify-center">
                    <flux:icon name="credit-card" class="w-8 h-8 text-white" />
                </div>
                <flux:button icon="plus-circle" color="primary" variant="primary" class="shadow-md hover:shadow-lg transition-all duration-200 w-full md:w-auto">
                    {{ __('Recharge Card') }}
                </flux:button>
            </div>
        </div>
    </div>
    
    <!-- Transactions History -->
    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-lg border border-zinc-200/50 dark:border-zinc-700 overflow-hidden transition-all duration-200 hover:shadow-xl">
        <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center gap-3">
            <flux:icon name="document-text" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
            <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">{{ __('Transaction History') }}</h2>
        </div>
        
        @if($operations->isEmpty())
            <div class="p-12 text-center">
                <div class="bg-zinc-100 dark:bg-zinc-700/30 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <flux:icon name="history" class="w-10 h-10 text-zinc-400 dark:text-zinc-600" />
                </div>
                <p class="text-zinc-600 dark:text-zinc-400 text-lg">{{ __('No transactions found.') }}</p>
                <p class="text-zinc-500 dark:text-zinc-500 text-sm mt-2">{{ __('Your transaction history will appear here.') }}</p>
            </div>
        @else
            <div class="p-6">
                <div class="overflow-x-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
                    <table class="w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                        <thead class="bg-zinc-50 dark:bg-zinc-900/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    {{ __('Date') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    {{ __('Type') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    {{ __('Description') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                    {{ __('Amount') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-800">
                            @foreach($operations as $operation)
                                <tr class="{{ $loop->even ? 'bg-zinc-50 dark:bg-zinc-700/30' : 'bg-white dark:bg-zinc-800' }} hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-400">
                                        {{ \Carbon\Carbon::parse($operation->date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($operation->type === 'credit')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-400">
                                                {{ __('Credit') }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-400">
                                                {{ __('Debit') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-400">
                                        @if($operation->debit_type === 'membership_fee')
                                            {{ __('Membership Fee Payment') }}
                                        @elseif($operation->debit_type === 'purchase')
                                            {{ __('Purchase') }}
                                        @elseif($operation->debit_type === 'order')
                                            {{ __('Order Purchase') }} 
                                            @if($operation->order_id)
                                                <span class="text-neutral-600 dark:text-neutral-400">#{{$operation->order_id}}</span>
                                            @endif
                                        @elseif($operation->credit_type === 'deposit')
                                            {{ __('Deposit') }}
                                        @elseif($operation->credit_type === 'payment')
                                            {{ __('Payment') }}
                                            @if($operation->payment_type)
                                                <span class="text-xs text-zinc-500 dark:text-zinc-500 ml-1">
                                                    via {{ $operation->payment_type }}
                                                </span>
                                            @endif
                                            @if($operation->payment_reference)
                                                <span class="text-xs text-zinc-500 dark:text-zinc-500 ml-1">
                                                    Ref: {{$operation->payment_reference}}
                                                </span>
                                            @endif
                                        @elseif($operation->credit_type === 'order_cancellation')
                                            {{ __('Order Cancellation') }} 
                                            @if($operation->order_id)
                                                <span class="text-neutral-600 dark:text-neutral-400">#{{$operation->order_id}}</span>
                                            @endif
                                        @else
                                            {{ __('Transaction') }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $operation->type === 'credit' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                        {{ $operation->type === 'credit' ? '+' : '-' }}{{ number_format($operation->value, 2) }} €
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($operations->hasPages())
                    <div class="px-6 py-4 mt-4 border-t border-zinc-200 dark:border-zinc-800 rounded-xl">
                        {{ $operations->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div> 