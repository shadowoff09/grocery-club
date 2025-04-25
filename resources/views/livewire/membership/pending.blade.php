<?php

use App\Services\Payment;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;


new #[Layout('components.layouts.app.sidebar')] class extends Component
{
    #[Validate('required|string|max:255|in:Visa,PayPal,MB WAY')]
    public string $default_payment_type = '';

    #[Validate('required|string|max:255')]
    public string $default_payment_reference = '';

    public function payFee(): void
    {
        $this->validate([
            'default_payment_type' => 'required|string|in:Visa,PayPal,MB WAY',
            'default_payment_reference' => [
                'required', 'string', 'max:255',
                function ($attr, $value, $fail) {
                    match ($this->default_payment_type) {
                        'Visa' => preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $value)
                            ?: $fail('The Visa card number must start with 4 and be 13 or 16 digits long.'),
                        'PayPal' => filter_var($value, FILTER_VALIDATE_EMAIL)
                            ?: $fail('Please enter a valid PayPal email address.'),
                        'MB WAY' => preg_match('/^9[1236][0-9]{7}$/', $value)
                            ?: $fail('Please enter a valid Portuguese mobile number (e.g., 9xx xxx xxx).'),
                        default => $fail('Invalid payment type selected.')
                    };
                }
            ]
        ]);

        $result = match ($this->default_payment_type) {
            'Visa' => Payment::payWithVisa($this->default_payment_reference),
            'PayPal' => Payment::payWithPayPal($this->default_payment_reference),
            'MB WAY' => Payment::payWithMBway($this->default_payment_reference),
            default => false
        };

        if (!$result) {
            Toaster::error('Payment failed. Please try again.');
        } else {
            $user = Auth::user();
            $user->type = 'member';
            $user->save();

            $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
        }
    }

    private function getPlaceholderForPaymentType(): string
    {
        return match ($this->default_payment_type) {
            'Visa' => 'Enter your 13 or 16 digit Visa card number',
            'PayPal' => 'Enter your PayPal email address',
            'MB WAY' => 'Enter your Portuguese mobile number (e.g., 9xx xxx xxx)',
            default => 'e.g., Visa number, PayPal email, or MB WAY number'
        };
    }
}
?>

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
            wire:model.live="default_payment_type"
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
            :placeholder="$this->getPlaceholderForPaymentType()"
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
