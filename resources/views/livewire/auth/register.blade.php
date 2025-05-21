<?php

use App\Models\User;
use App\Models\Card;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;


new #[Layout('components.layouts.auth')] class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $gender = '';
    public ?string $default_delivery_address = null;
    public ?string $nif = null;
    public ?string $default_payment_type = null;
    public ?string $default_payment_reference = null;
    public bool $show_optional = false;
    public $photo = null;
    public array $basicData = [];
    public $redirectTo = null;
    public $redirectMessage = null;

    public function mount()
    {
        $this->redirectTo = Request::query('redirect_to');

        if ($this->redirectTo === 'checkout') {
            $this->redirectMessage = __('After creating your account, you will be redirected to pay your membership fee and then redirected to the checkout page.');
        }
    }

    public function proceed(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'gender' => ['required', 'string', 'in:M,F']
        ]);

        $this->basicData = $validated;
        $this->basicData['password'] = Hash::make($validated['password']);
        $this->show_optional = true;
    }

    public function register(): void
    {
        $validationRules = [
            'default_delivery_address' => ['nullable', 'string', 'max:255'],
            'nif' => ['nullable', 'string', 'max:9', 'min:9', 'regex:/^[0-9]{9}$/'],
            'default_payment_type' => ['nullable', 'string', 'max:255', 'in:Visa,PayPal,MB WAY'],
            'photo' => ['nullable', 'image', 'max:8096', 'mimes:jpg,jpeg,png'],
        ];

        // Only validate payment reference if payment type is selected
        if ($this->default_payment_type) {
            $validationRules['default_payment_reference'] = [
                'required', 'string', 'max:255',
                function ($attr, $value, $fail) {
                    if (!$value) return;

                    match ($this->default_payment_type) {
                        'Visa' => preg_match('/^[1-9][0-9]{15}$/', $value) && !str_ends_with($value, '2')
                            ?: $fail('The Visa card must be 16 digits long, cannot start with 0, and cannot end with 2.'),
                        'PayPal' => filter_var($value, FILTER_VALIDATE_EMAIL)
                            ?: $fail('Please enter a valid PayPal email address.'),
                        'MB WAY' => preg_match('/^9[1236][0-9]{7}$/', $value) && !str_ends_with($value, '2')
                            ?: $fail('Please enter a valid Portuguese mobile number that doesn\'t end with 2.'),
                        default => true
                    };
                }
            ];
        } else {
            $validationRules['default_payment_reference'] = ['nullable', 'string', 'max:255'];
        }

        $validated = $this->validate($validationRules);

        $validated = array_merge($this->basicData, $validated);
        $validated['type'] = 'pending_member';

        // Handle the photo before creating the user
        if ($this->photo) {
            // Get file extension
            $extension = $this->photo->getClientOriginalExtension();

            // Create a unique filename with timestamp
            $filename = time() . '_' . uniqid('', true) . '.' . $extension;

            // Store the file with the unique name
            $this->photo->storeAs('users', $filename, 'public');

            // Add the filename to validated data
            $validated['photo'] = $filename;
        }

        // Create the user with all data including the photo filename
        $user = User::create($validated);

        event(new Registered($user));

        Card::create([
            'id' => $user->id,
            'card_number' => random_int(100000, 999999),
            'balance' => 0.00
        ]);

        Auth::login($user);

        if ($this->redirectTo === 'checkout') {
            $this->redirect(route('membership.pending'), navigate: true);
        } else {
            $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
        }
    }

    private function getPaymentReferencePlaceholder(): string
    {
        return match ($this->default_payment_type) {
            'Visa' => 'Enter your 16 digit Visa card (cannot start with 0 or end with 2)',
            'PayPal' => 'Enter your PayPal email address',
            'MB WAY' => 'Enter your Portuguese mobile number (e.g., 9xx xxx xxx)',
            default => 'e.g., Visa number, PayPal email, or MB WAY number'
        };
    }
};
?>

<div class="flex flex-col gap-6 w-full max-w-xl mx-auto">
    <x-auth-header
        :title="$show_optional
        ? __('Optional Information')
        : __('Create an account')"

        :description="$show_optional
        ? __('Provide optional details to complete your profile')
        : __('Enter your details below to create your account')"
    />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')"/>

    @if($redirectMessage)
        <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 text-indigo-700 dark:text-indigo-300 px-4 py-3 rounded-lg relative" role="alert">
            <div class="flex">
                <flux:icon name="arrow-right-circle" class="w-5 h-5 text-indigo-500 dark:text-indigo-400 mr-3 flex-shrink-0" />
                <div>{{ $redirectMessage }}</div>
            </div>
        </div>
    @endif

    @if(!$this->show_optional)

        <form wire:submit="proceed" class="flex flex-col gap-4">
            <!-- Name -->
            <flux:input
                wire:model="name"
                :label="__('Name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Full name')"
            />

            <!-- Email Address -->
            <flux:input
                wire:model="email"
                :label="__('Email address')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
            />

            <!-- Confirm Password -->
            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
            />

            <!-- Gender -->
            <flux:select wire:model="gender" :label="__('Gender')" placeholder="Choose gender..." required>
                <flux:select.option value="M">{{ __('Male') }}</flux:select.option>
                <flux:select.option value="F">{{ __('Female') }}</flux:select.option>
            </flux:select>


            <flux:button type="submit" variant="primary" class="w-full cursor-pointer">
                <div class="flex items-center justify-between">
                    {{ __('Next') }}
                    <x-lucide-arrow-right class="w-4 h-4"/>
                </div>
            </flux:button>
        </form>
    @else
        <form wire:submit="register" class="flex flex-col gap-4">
            <!-- Delivery Address -->
            <flux:input
                wire:model="default_delivery_address"
                :label="__('Delivery address')"
                type="text"
                autocomplete="street-address"
                :placeholder="__('Delivery address')"
            />

            <!-- NIF -->
            <flux:input
                wire:model="nif"
                :label="__('NIF')"
                type="text"
                autocomplete="off"
                :placeholder="__('Tax identification number')"
            />

            <!-- Preferred Payment Method -->
            <flux:select
                wire:model.live="default_payment_type"
                :label="__('Preferred payment method')"
                placeholder="Choose..."
            >
                <flux:select.option value="Visa">Visa</flux:select.option>
                <flux:select.option value="PayPal">PayPal</flux:select.option>
                <flux:select.option value="MB WAY">MB WAY</flux:select.option>
            </flux:select>

            <!-- Default Payment Reference -->
            <flux:input
                wire:model="default_payment_reference"
                :label="__('Payment reference')"
                type="text"
                autocomplete="off"
                :placeholder="$this->getPaymentReferencePlaceholder()"
            />

            <!-- Profile Photo -->
            <flux:input
                wire:model="photo"
                :label="__('Profile photo')"
                type="file"
                accept="image/*"
            />

            <flux:button type="submit" variant="primary" class="w-full cursor-pointer">
                {{ __('Register') }}
            </flux:button>
        </form>
    @endif

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Already have an account?') }}
        @if($redirectTo)
            <flux:link :href="route('login', ['redirect_to' => $redirectTo])" wire:navigate>{{ __('Log in') }}</flux:link>
        @else
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        @endif
    </div>
</div>
