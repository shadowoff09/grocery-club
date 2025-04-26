<?php

use App\Models\Card;
use App\Models\Operation;
use App\Models\Setting;
use App\Models\User;
use App\Services\Payment;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;


new #[Layout('components.layouts.app.sidebar')] class extends Component {
    #[Validate('required|string|max:255|in:Visa,PayPal,MB WAY')]
    public string $default_payment_type = '';

    #[Validate('required|string|max:255')]
    public string $default_payment_reference = '';

    public float $membershipFee = 0;

    public function mount(): void
    {
        // Get the membership fee from the settings table
        $this->membershipFee = Setting::getMembershipFee();
    }


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
            $userCard = $user->card;

            // Use DB transaction to ensure all operations are atomic
            DB::transaction(function () use ($user, $userCard) {
                // 1. Create the operation record for membership fee payment
                Operation::create([
                    'card_id' => $userCard->id,
                    'type' => 'debit', // It's a debit operation because membership fee is paid
                    'value' => $this->membershipFee,
                    'date' => now()->toDateString(),
                    'debit_type' => 'membership_fee',
                    'payment_type' => $this->default_payment_type,
                    'payment_reference' => $this->default_payment_reference,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 2. Update the card balance - add the fee to the card
                $userCard->balance += $this->membershipFee;
                $userCard->save();

                // 3. Update user type and payment preferences
                $user->type = 'member';
                $user->save();
            });

            // Show success message and redirect
            session()->flash('success', 'Membership fee payment successful! Your account has been activated and your card has been loaded with the membership fee amount.');
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

<div
    class="mt-32 flex flex-col gap-6 w-full max-w-lg mx-auto border dark:border-stone-500 border-stone-300 rounded-lg p-6">
    <flux:callout icon="clock" color="yellow" inline>
        <flux:callout.heading>Account Incomplete</flux:callout.heading>
        <flux:callout.text>
            Your account is currently in a <strong>pending</strong> state.
            <br>
            To access all features, you need to pay your membership fee of
            <strong>{{ number_format($membershipFee, 2) }} €</strong>.
        </flux:callout.text>
    </flux:callout>


    <form wire:submit="payFee" class=" flex flex-col gap-4">
        <div>
            <h1 class="text-2xl font-bold dark:text-white text-gray-900">
                {{ __('Complete Your Membership') }}
            </h1>
            <p class="text-sm dark:text-gray-300 text-gray-600">
                Please select your preferred payment method and enter the required details.
            </p>
        </div>
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
                    <strong>{{ number_format($membershipFee, 2) }} €</strong>
                </span>
            <span wire:loading wire:target="payFee">
                    Processing...
            </span>
        </flux:button>

        <flux:separator/>

        <p class="text-sm dark:text-gray-300 text-gray-600 text-center">
            Secure payment processing. Your data is encrypted.
        </p>

    </form>
</div>
