<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $gender = '';
    public ?string $default_delivery_address = '';
    public ?string $nif = '';
    public ?string $default_payment_type = '';
    public ?string $default_payment_reference = '';
    public $photo = null;
    public $newPhoto = null;


    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->gender = $user->gender;
        $this->default_delivery_address = $user->default_delivery_address ?? '';
        $this->nif = $user->nif ?? '';
        $this->default_payment_type = $user->default_payment_type ?? '';
        $this->default_payment_reference = $user->default_payment_reference ?? '';
        $this->photo = $user->photo;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
            'gender' => [
                'required',
                'string',
                'max:1'
            ],
            'default_delivery_address' => ['nullable', 'string', 'max:255'],
            'nif' => ['nullable', 'string', 'regex:/^[0-9]{9}$/'], // 9 digit Portuguese NIF format
            'default_payment_type' => ['nullable', 'string', 'in:Visa,PayPal,MB WAY'],
            'default_payment_reference' => [
                'nullable', 
                'string', 
                'max:255',
                function ($attr, $value, $fail) {
                    if (!$this->default_payment_type || !$value) {
                        return;
                    }
                    
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
            ],
            'newPhoto' => ['nullable', 'image', 'max:2048'] // 2MB max
        ]);

        // Handle photo upload if provided
        if ($this->newPhoto) {
            if ($user->photo) {
                // Delete old photo
                Storage::disk('public')->delete('users/' . $user->photo);
            }

            // Store new photo
            $photoName = time() . '.' . $this->newPhoto->getClientOriginalExtension();
            $this->newPhoto->storeAs('users', $photoName, 'public');
            $user->photo = $photoName;
        }

        // Remove newPhoto from validated data to avoid error when filling
        unset($validated['newPhoto']);

        // Convert empty strings to null for enum fields that require it
        if (empty($validated['default_payment_type'])) {
            $validated['default_payment_type'] = null;
        }
        if (empty($validated['default_payment_reference'])) {
            $validated['default_payment_reference'] = null;
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Get placeholder text for payment reference field based on selected payment type
     */
    public function getPaymentReferencePlaceholder(): string
    {
        return match ($this->default_payment_type) {
            'Visa' => 'Enter your Visa card (16 digits, cannot start with 0 or end with 2)',
            'PayPal' => 'Enter your PayPal email address',
            'MB WAY' => 'Enter your Portuguese mobile number (cannot end with 2)',
            default => 'Enter your payment reference'
        };
    }
}; ?>


<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your information here.')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <!-- Profile Photo -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">{{ __('Profile Photo') }}</label>
                <div class="flex items-center space-x-4">
                    <div class="relative w-16 h-16 rounded-lg overflow-hidden bg-gray-100 dark:bg-zinc-700">
                        @if($newPhoto)
                            <img src="{{ $newPhoto->temporaryUrl() }}"
                                 alt="{{ __('New profile photo') }}"
                                 class="w-full h-full object-cover">
                        @elseif($photo)
                            <img src="{{ asset('storage/users/' . $photo) }}"
                                 alt="{{ __('Profile photo') }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div
                                class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                {{ auth()->user()->initials() }}
                            </div>
                        @endif

                        @if($newPhoto)
                            <div class="absolute bottom-0 right-0 bg-green-500 rounded-full p-1">
                                <flux:icon name="check" class="w-3 h-3 text-white" />
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="relative">
                            <input type="file" wire:model="newPhoto" class="hidden" id="photo" accept="image/*">
                            <label for="photo"
                                   class="px-4 py-2 bg-zinc-800 dark:bg-zinc-600 text-white rounded-md cursor-pointer inline-block">
                                {{ $newPhoto ? __('Change Photo') : __('Choose Photo') }}
                            </label>

                            <div wire:loading wire:target="newPhoto" class="mt-2 text-sm text-blue-500 flex items-center">
                                <flux:icon name="arrow-path" class="w-4 h-4 animate-spin mr-2" />
                                {{ __('Uploading...') }}
                            </div>
                        </div>

                        @if($newPhoto)
                            <div class="text-xs text-green-500 mt-1">
                                {{ __('New photo selected. Save to apply changes.') }}
                            </div>
                        @endif

                        <div class="text-xs text-gray-500 mt-1">{{ __('JPG, PNG or GIF. 2MB max.') }}</div>
                        @error('newPhoto') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>


            <!-- Basic Information -->
            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name"/>

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email"/>

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer"
                                       wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <flux:select wire:model="gender" :label="__('Gender')" placeholder="Choose gender..." required>
                <flux:select.option value="M">{{ __('Male') }}</flux:select.option>
                <flux:select.option value="F">{{ __('Female') }}</flux:select.option>
            </flux:select>

            <!-- Additional Information -->
            <flux:input wire:model="default_delivery_address" :label="__('Delivery Address (optional)')" type="text"/>

            <flux:input wire:model="nif" :label="__('NIF (optional)')" type="text" maxlength="9"/>

            <flux:select wire:model="default_payment_type" :label="__('Preferred Payment Method (optional)')"
                         placeholder="Choose payment method...">
                <flux:select.option value="Visa">{{ __('Visa') }}</flux:select.option>
                <flux:select.option value="PayPal">{{ __('PayPal') }}</flux:select.option>
                <flux:select.option value="MB WAY">{{ __('MB WAY') }}</flux:select.option>
            </flux:select>

            <flux:input wire:model="default_payment_reference" :label="__('Preferred Payment Reference (optional)')"
                        type="text" placeholder="{{ $this->getPaymentReferencePlaceholder() }}"/>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full cursor-pointer">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>

