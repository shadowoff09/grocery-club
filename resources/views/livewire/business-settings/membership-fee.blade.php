<?php

use App\Models\Setting;
use Livewire\Volt\Component;

new class extends Component {
    private const MIN_FEE = 0.00;
    private const DECIMAL_PLACES = 2;

    #[Rule('required|numeric|min:0')]
    public float $membership_fee = 0.0;

    public function mount(): void
    {
        $this->membership_fee = Setting::getMembershipFee();
    }

    public function updateMembershipFee(): void
    {
        $currentFee = Setting::getMembershipFee();

        $this->validate([
            'membership_fee' => [
                'required',
                'numeric',
                'min:' . self::MIN_FEE,
                function($attribute, $value, $fail) use ($currentFee) {
                    if ((float)$value === (float)$currentFee) {
                        $fail(__('The new membership fee must be different from the current fee, which is ' . $currentFee . '$.'));
                    }
                }
            ]
        ]);

        try {
            $setting = Setting::query()->firstOrFail();
            $setting->update([
                'membership_fee' => round($this->membership_fee, self::DECIMAL_PLACES)
            ]);

            $this->dispatch('membership-fee-updated');
            session()->flash('success', __('Membership fee updated successfully'));
        } catch (\Exception $e) {
            session()->flash('error', __('Failed to update membership fee'));
        }
    }

}; ?>

<section class="w-full">
    @include('partials.business-settings-heading')

    <x-board.settings.layout :heading="__('Membership Fee')"
                             :subheading=" __('Update the membership fee for the business')">

        <flux:input
            wire:model="membership_fee"
            :label="__('Membership Fee')"
            type="number"
            required
            placeholder="Enter membership fee"
            min="0"
            step="0.01"
            class="w-full"
            prefix="$"
        />
        <flux:subheading>
            {{ __('The membership fee is the amount charged to users for accessing premium features.') }}
        </flux:subheading>

        <div class="flex items-center gap-4">
            <div class="flex items-center">
                <flux:button
                    wire:click="updateMembershipFee"
                    type="button"
                    variant="primary"
                >
                    {{ __('Update Membership Fee') }}
                </flux:button>
            </div>

            <x-action-message class="flex items-center" on="membership-fee-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>

    </x-board.settings.layout>
</section>
