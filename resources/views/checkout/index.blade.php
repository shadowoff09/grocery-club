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
        
        <livewire:checkout />
    </div>
</x-layouts.app.header> 