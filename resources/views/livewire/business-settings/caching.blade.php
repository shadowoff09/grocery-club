<?php

use App\Models\Setting;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Masmerise\Toaster\Toaster;

new class extends Component {


    /**
     * Flush the application cache.
     */
    public function flushCache(): void
    {
        Cache::flush();
        $this->modal('confirm-flush-cache')->close();
        Toaster::success('Cache flushed successfully!');
    }

}; ?>

<section class="w-full">
    @include('partials.business-settings-heading')

    <x-board.settings.layout :heading="__('Caching Settings')"
                             :subheading=" __('Update the caching settings for your business')">

        <div class="relative mb-5">
            <flux:heading>{{ __('Flush Cache') }}</flux:heading>
            <flux:subheading>{{ __('Deletes all application cache') }}</flux:subheading>
        </div>
        <flux:modal.trigger name="confirm-flush-cache" >
            <flux:button variant="danger" x-data="" class="cursor-pointer" x-on:click.prevent="$dispatch('open-modal', 'confirm-cache-flush')">
                {{ __('Flush Cache') }}
            </flux:button>
        </flux:modal.trigger>

        <flux:modal name="confirm-flush-cache" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
            <form wire:submit="flushCache" class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Are you sure you want to flush the application cache?') }}</flux:heading>

                    <flux:subheading>{{ __('This action cannot be undone.') }}</flux:subheading>
                </div>

                <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                    <flux:modal.close>
                        <flux:button variant="filled" class="cursor-pointer">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>

                    <flux:button variant="danger" type="submit" class="cursor-pointer">{{ __('Flush Cache') }}</flux:button>
                </div>
            </form>
        </flux:modal>
    </x-board.settings.layout>
</section>
