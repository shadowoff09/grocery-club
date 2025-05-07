<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <h1 class="text-3xl font-bold mb-8 text-zinc-900 dark:text-zinc-100">{{ __('Your Balance') }}</h1>
    
    <!-- Card Balance Summary -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-zinc-700 dark:text-zinc-300 mb-1">{{ __('Current Balance') }}</h2>
                <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                    {{ number_format($card->balance, 2) }} €
                </div>
            </div>
            <div class="bg-indigo-100 dark:bg-indigo-900/30 rounded-full p-4">
                <flux:icon name="credit-card" class="w-8 h-8 text-indigo-600 dark:text-indigo-400" />
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between">
            <div class="flex items-center">
                <span class="text-zinc-600 dark:text-zinc-400">{{ __('Card Number:') }}</span>
                <span class="ml-2 font-medium text-zinc-800 dark:text-zinc-200">{{ $card->card_number }}</span>
            </div>
            <flux:button icon="plus-circle" color="indigo">
                {{ __('Recharge Card') }}
            </flux:button>
        </div>
    </div>
    
    <!-- Transactions History -->
    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-800">
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Transaction History') }}</h2>
        </div>
        
        @if($operations->isEmpty())
            <div class="p-6 text-center">
                <flux:icon name="history" class="w-12 h-12 mx-auto text-zinc-300 dark:text-zinc-700 mb-4" />
                <p class="text-zinc-600 dark:text-zinc-400">{{ __('No transactions found.') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/50">
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
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-800">
                        @foreach($operations as $operation)
                            <tr>
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
                                    @elseif($operation->credit_type === 'deposit')
                                        {{ __('Deposit') }}
                                    @else
                                        {{ __('Transaction') }}
                                    @endif
                                    
                                    @if($operation->payment_type)
                                        <span class="text-xs text-zinc-500 dark:text-zinc-500 ml-1">
                                            via {{ $operation->payment_type }}
                                        </span>
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
            <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800">
                {{ $operations->links() }}
            </div>
        @endif
    </div>
</div> 