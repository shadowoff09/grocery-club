<?php

use App\Models\Setting;
use App\Services\Payment;
use App\Traits\WithCardOperations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Masmerise\Toaster\Toaster;

new #[Layout('components.layouts.app.sidebar')] class extends Component {
    use WithCardOperations;
    
    #[Validate('required|string|max:255|in:Visa,PayPal,MB WAY')]
    public string $default_payment_type = '';

    #[Validate('required|string|max:255')]
    public string $default_payment_reference = '';

    #[Validate('required_if:default_payment_type,Visa|nullable|string|max:4')]
    public ?string $cvc_code = '';

    public float $membershipFee = 0;
    public $redirectTo = null;
    public bool $hasDefaults = false;
    public bool $showDefaultsAlert = false;
    public ?string $savedPaymentType = null;
    public ?string $savedPaymentReference = null;
    public bool $saveAsDefault = false;

    public function mount(): void
    {
        // Get the membership fee from the settings table
        $this->membershipFee = Setting::getMembershipFee();
        $this->redirectTo = Request::query('redirect_to');
        
        // Get default payment method via trait
        $defaultPayment = $this->getDefaultPaymentMethod();
        if ($defaultPayment) {
            $this->hasDefaults = true;
            $this->showDefaultsAlert = true;
            $this->savedPaymentType = $defaultPayment['method'];
            $this->savedPaymentReference = $defaultPayment['reference'];
        }
    }

    public function useDefaults(): void
    {
        if ($this->savedPaymentType && $this->savedPaymentReference) {
            $this->default_payment_type = $this->savedPaymentType;
            $this->default_payment_reference = $this->savedPaymentReference;
            $this->showDefaultsAlert = false; // Hide the alert after using defaults
        }
    }

    public function payFee(): void
    {
        try {
            // Use trait to validate payment details
            $this->validatePaymentDetails(
                $this->membershipFee,
                $this->default_payment_type,
                $this->default_payment_reference,
                $this->cvc_code
            );
            
            // Process payment through payment service using trait method
            $paymentResult = match ($this->default_payment_type) {
                'Visa' => Payment::payWithVisa($this->default_payment_reference, $this->cvc_code),
                'PayPal' => Payment::payWithPayPal($this->default_payment_reference),
                'MB WAY' => Payment::payWithMBway($this->default_payment_reference),
                default => false
            };
            
            if (!$paymentResult) {
                Toaster::error('Payment failed. Please try again.');
                return;
            }
            
            $user = Auth::user();
            
            if (!$this->hasCard()) {
                Toaster::error('Card not found.');
                return;
            }
            
            // Use DB transaction to ensure all operations are atomic
            DB::transaction(function () use ($user) {
                // Add membership fee to card balance
                $this->performCardTransaction(
                    $this->membershipFee,
                    'credit',
                    [
                        'credit_type' => 'payment',
                        'payment_type' => $this->default_payment_type,
                        'payment_reference' => $this->default_payment_reference,
                    ]
                );
                
                // Create a debit operation for the membership fee
                $this->performCardTransaction(
                    $this->membershipFee,
                    'debit',
                    [
                        'debit_type' => 'membership_fee',
                        'payment_type' => $this->default_payment_type,
                        'payment_reference' => $this->default_payment_reference,
                    ]
                );
                
                // Update user type
                $user->type = 'member';
                
                // Save payment info as default if checkbox is checked
                if ($this->saveAsDefault) {
                    $this->saveDefaultPaymentMethod(
                        $this->default_payment_type,
                        $this->default_payment_reference
                    );
                }
                
                $user->save();
            });
            
            // Show success message and redirect
            session()->flash('success', 'Membership fee payment successful! Your account has been activated and your card has been loaded with the membership fee amount.');
            
            // Redirect to checkout if specified, otherwise to dashboard
            if ($this->redirectTo === 'checkout') {
                $this->redirect(route('checkout'), navigate: true);
            } else {
                $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
            }
            
        } catch (\Exception $e) {
            Toaster::error('Error processing payment: ' . $e->getMessage());
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
    
    public function updated($field): void
    {
        // Check if payment info is different from saved info
        if (($field === 'default_payment_type' || $field === 'default_payment_reference') && 
            $this->hasDefaults && 
            ($this->default_payment_type !== $this->savedPaymentType || 
             $this->default_payment_reference !== $this->savedPaymentReference)) {
            // Different payment info provided, show the save option
            $this->saveAsDefault = false;
        }
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
        
        @if($showDefaultsAlert)
        <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/40 rounded-lg p-3 mb-2">
            <div class="flex flex-col gap-2">
                <p class="text-sm text-indigo-700 dark:text-indigo-300">
                    You have saved payment preferences:
                    <span class="font-medium">{{ $savedPaymentType }}</span>
                    @if($savedPaymentType === 'Visa')
                        ending in {{ substr($savedPaymentReference, -4) }}
                    @elseif($savedPaymentType === 'PayPal')
                        ({{ $savedPaymentReference }})
                    @elseif($savedPaymentType === 'MB WAY')
                        ({{ $savedPaymentReference }})
                    @endif
                </p>
                <flux:button wire:click="useDefaults" class="text-xs w-full">
                    Use saved payment details
                </flux:button>
            </div>
        </div>
        @endif
        
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
            wire:model.live="default_payment_reference"
            :label="__('Payment reference')"
            type="text"
            required
            :placeholder="$this->getPlaceholderForPaymentType()"
        />

        @if($default_payment_type === 'Visa')
            <flux:input
                wire:model="cvc_code"
                :label="__('CVC Code')"
                type="text"
                required
                placeholder="Enter 3-digit security code"
                maxlength="3"
            />
        @endif

        @if($hasDefaults && ($default_payment_type !== $savedPaymentType || $default_payment_reference !== $savedPaymentReference) && !empty($default_payment_type) && !empty($default_payment_reference))
        <div class="mt-2">
            <flux:checkbox
                wire:model="saveAsDefault"
                :label="__('You\'ve entered new payment information. Save as your default payment method?')"
            />
        </div>
        @endif

        <flux:button type="submit" variant="primary" class="w-full cursor-pointer" wire:loading.attr="disabled">
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
