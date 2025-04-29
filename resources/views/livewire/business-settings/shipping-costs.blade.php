<?php

use App\Models\SettingsShippingCost;
use Livewire\Volt\Component;
use Livewire\Attributes\On;
use Masmerise\Toaster\Toaster;


new class extends Component {
    public array $shippingCosts = [];
    public ?int $editId = null;
    public float $min_value_threshold = 0;
    public float $max_value_threshold = 0;
    public float $shipping_cost = 0;
    public bool $isEditing = false;

    protected $listeners = [
        'editShippingCost',
        'shippingCostChanged' => 'refreshShippingCosts'
    ];

    public function mount(): void
    {
        $this->refreshShippingCosts();
    }

    public function refreshShippingCosts(): void
    {
        $this->shippingCosts = SettingsShippingCost::orderBy('min_value_threshold')->get()->toArray();
    }

    #[Livewire\Attributes\On('editShippingCost')]
    public function editShippingCost(int $id): void
    {
        $entry = SettingsShippingCost::findOrFail($id);

        $this->editId = $entry->id;
        $this->min_value_threshold = (float)$entry->min_value_threshold;
        $this->max_value_threshold = (float)$entry->max_value_threshold;
        $this->shipping_cost = (float)$entry->shipping_cost;
        $this->isEditing = true;
    }


    public function delete(int $id): void
    {
        SettingsShippingCost::findOrFail($id)->delete();
        $this->refreshShippingCosts();
        Toaster::success('Shipping cost deleted successfully!');
    }

    public function save(): void
    {
        $this->validate([
            'min_value_threshold' => 'required|numeric|gte:0|max:9999999.99',
            'max_value_threshold' => 'required|numeric|gt:min_value_threshold|max:9999999.99',
            'shipping_cost' => 'required|numeric|min:0|max:9999999.99',
        ]);

        if ($this->editId) {
            $existingRecord = SettingsShippingCost::find($this->editId);

            // Only check for overlaps if thresholds have changed
            if ($existingRecord->min_value_threshold != $this->min_value_threshold ||
                $existingRecord->max_value_threshold != $this->max_value_threshold) {

                $overlaps = SettingsShippingCost::query()
                    ->where('id', '!=', $this->editId)
                    ->where(function ($q) {
                        $q->whereBetween('min_value_threshold', [$this->min_value_threshold, $this->max_value_threshold])
                            ->orWhereBetween('max_value_threshold', [$this->min_value_threshold, $this->max_value_threshold])
                            ->orWhere(function ($q) {
                                $q->where('min_value_threshold', '<=', $this->min_value_threshold)
                                    ->where('max_value_threshold', '>=', $this->max_value_threshold);
                            });
                    })
                    ->exists();

                if ($overlaps) {
                    $this->addError('min_value_threshold', 'This interval overlaps with another shipping cost range.');
                    return;
                }
            }
        } else {
            // For new records, always check for overlaps
            $overlaps = SettingsShippingCost::query()
                ->where(function ($q) {
                    $q->whereBetween('min_value_threshold', [$this->min_value_threshold, $this->max_value_threshold])
                        ->orWhereBetween('max_value_threshold', [$this->min_value_threshold, $this->max_value_threshold])
                        ->orWhere(function ($q) {
                            $q->where('min_value_threshold', '<=', $this->min_value_threshold)
                                ->where('max_value_threshold', '>=', $this->max_value_threshold);
                        });
                })
                ->exists();

            if ($overlaps) {
                $this->addError('min_value_threshold', 'This interval overlaps with another shipping cost range.');
                return;
            }
        }

        SettingsShippingCost::updateOrCreate(
            ['id' => $this->editId],
            [
                'min_value_threshold' => $this->min_value_threshold,
                'max_value_threshold' => $this->max_value_threshold,
                'shipping_cost' => $this->shipping_cost,
            ]
        );

        $this->reset(['editId', 'min_value_threshold', 'max_value_threshold', 'shipping_cost']);
        $this->refreshShippingCosts();
        $this->dispatch('shippingCostChanged')->to('shipping-costs-table');
        Toaster::success('Shipping cost saved successfully!');
        $this->isEditing = false;
    }

    public function cancel()
    {
        $this->reset(['editId', 'min_value_threshold', 'max_value_threshold', 'shipping_cost']);
        $this->isEditing = false;
    }
}; ?>

<section class="w-full">
    @include('partials.business-settings-heading')

    <x-board.settings.layout
        :heading="__('Shipping Costs')"
        :subheading="__('Update the shipping costs for the business')">

        <div class="space-y-4">
            @if($isEditing)
                <flux:callout icon="pencil" color="blue">
                    <flux:callout.heading>Edit Mode</flux:callout.heading>
                    <flux:callout.text>
                        You are currently editing a shipping cost entry. Please ensure that the values are correct
                        before saving.
                    </flux:callout.text>
                </flux:callout>
            @endif
            <form wire:submit="save" class="space-y-4">
                <flux:input wire:model="min_value_threshold" :label="__('Minimum Threshold')" type="text"/>
                <flux:input wire:model="max_value_threshold" :label="__('Maximum Threshold')" type="text"/>
                <flux:input wire:model="shipping_cost" :label="__('Shipping Cost')" type="text"/>

                <div class="flex space-x-2">
                    <flux:button type="submit"
                                 icon="check"
                                 class="px-4 py-2 cursor-pointer"
                                 variant="primary"
                    >
                        {{ $editId ? __('Update') : __('Add') }} Shipping Cost
                    </flux:button>
                    @if($editId)
                        <flux:button
                            wire:click="cancel"
                            variant="danger"
                            icon="x-mark"
                            class="px-4 py-2 cursor-pointer">
                            {{ __('Cancel') }}
                        </flux:button>
                    @endif
                </div>
            </form>
        </div>


        <livewire:shipping-costs-table/>
    </x-board.settings.layout>
</section>

