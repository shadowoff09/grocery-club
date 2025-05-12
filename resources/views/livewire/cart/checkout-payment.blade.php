<div class="space-y-5">
    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800 flex items-center">
        <flux:icon name="credit-card" class="w-5 h-5 text-blue-500 mr-3"/>
        <div>
            <span class="font-medium text-blue-700 dark:text-blue-300">Card Balance:</span>
            <span class="font-semibold text-zinc-900 dark:text-zinc-100">${{ isset($cardBalance) ? number_format($cardBalance, 2) : '--.--' }}</span>
        </div>
    </div>
    
    <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-4 px-6 rounded-lg transition-colors flex items-center justify-center space-x-2">
        <flux:icon name="credit-card" class="w-5 h-5"/>
        <span>Complete Purchase</span>
    </button>
</div>
