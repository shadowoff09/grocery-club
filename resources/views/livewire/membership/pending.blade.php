<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;


new #[Layout('components.layouts.app.sidebar')] class extends Component {
    #[Validate('required|string|max:255|in:Visa,PayPal,MB WAY')]
    public string $default_payment_type = '';

    #[Validate('required|string|max:255')]
    public string $default_payment_reference = '';

    /**
     * Handle the payment process.
     */
    public function payFee(): void
    {
        $validated = $this->validate([
            'default_payment_type' => ['required', 'string', 'max:255', 'in:Visa,PayPal,MB WAY'],
            'default_payment_reference' => [
                'required',
                'string',
                'max:255']
        ]);

        dd($validated);
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

}; ?>

<div class="mt-4 flex flex-col gap-6">
    <flux:callout icon="clock" color="yellow" inline>
        <flux:callout.heading>Account Incomplete</flux:callout.heading>
        <flux:callout.text>
            Your account is currently in a <strong>pending</strong> state.
            <br>
            To access all features, you need to pay your membership fee.
        </flux:callout.text>
    </flux:callout>

    <form wire:submit="payFee" class="w-full max-w-xl mx-auto flex flex-col gap-4">
        <flux:select
            wire:model="default_payment_type"
            :label="__('Payment Method')"
            placeholder="Choose payment method..."
            required
        >
            <flux:select.option value="Visa">Visa</flux:select.option>
            <flux:select.option value="PayPal">PayPal</flux:select.option>
            <flux:select.option value="MB WAY">MB WAY</flux:select.option>
        </flux:select>

        <flux:input
            wire:model="default_payment_reference"
            :label="__('Payment reference')"
            type="text"
            required
            :placeholder="__('e.g., Visa number, PayPal email, or MB WAY number')"
        />

        <flux:button type="submit" variant="primary" class="w-full" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="payFee">
                    {{ __('Pay Fee') }}
                </span>
            <span wire:loading wire:target="payFee">
                    Processing...
                </span>
        </flux:button>

    </form>
</div>
