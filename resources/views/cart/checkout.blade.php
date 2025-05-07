@php
    use Illuminate\Support\Facades\Auth;
@endphp

<x-layouts.app.header :title="__('Checkout')">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-black dark:text-white">{{ __('Checkout') }}</h1>
        
        @if(Auth::check() && Auth::user()->isPendingMember())
            <flux:callout icon="clock" color="amber" class="mb-6" inline>
                <flux:callout.heading>Account Not Activated</flux:callout.heading>
                <flux:callout.text>
                    Your account is currently in a <strong>pending</strong> state.
                    <br>
                    To complete this purchase, you need to pay your membership fee.
                </flux:callout.text>
                <x-slot name="actions" class="@md:h-full m-0!">
                    <a href="{{ route('membership.pending', ['redirect_to' => 'checkout']) }}">
                        <flux:button class="cursor-pointer">Pay Membership Fee -></flux:button>
                    </a>
                </x-slot>
            </flux:callout>
        @endif
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Summary -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-6 mb-6">
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('Order Items') }}</h2>
                    
                    <livewire:cart.checkout-items />
                </div>
                
                <!-- Checkout form would go here in the future -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-6">
                    <p class="text-center text-lg text-zinc-600 dark:text-zinc-400 mb-4">
                        {{ __('Your checkout page is being prepared. Please check back soon.') }}
                    </p>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm p-6 sticky top-6">
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-4">{{ __('Order Summary') }}</h2>
                    
                    <livewire:cart.checkout-summary />
                    
                    <div class="flex mt-6">
                        <a href="{{ route('cart.index') }}" 
                           class="inline-flex items-center px-6 py-3 text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-zinc-100 font-medium
                           transition-colors rounded-lg border border-zinc-200 dark:border-zinc-700 hover:border-zinc-300 dark:hover:border-zinc-600">
                            <x-lucide-arrow-left class="w-4 h-4 mr-2"/>
                            {{ __('Back to Cart') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app.header> 