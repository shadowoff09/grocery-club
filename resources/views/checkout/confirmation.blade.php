<x-layouts.app.header :title="__('Order Confirmation')">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-md mx-auto bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-8">
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
            
            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-6 mt-6">
                <p class="text-zinc-600 dark:text-zinc-400 mb-6 text-center">
                    {{ __('A confirmation has been sent to your email.') }}
                </p>
                
                <div class="flex justify-center">
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium
                       transition-colors rounded-lg">
                        {{ __('Return to Home') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app.header> 