<?php

namespace App\Livewire;

use App\Models\SettingsShippingCost;
use Blade;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Masmerise\Toaster\Toaster;

final class ShippingCostsTable extends PowerGridComponent
{
    public string $tableName = 'shipping-costs-table';

    protected $listeners = ['shippingCostChanged' => 'refreshTable'];

    public function refreshTable(): void
    {
        $this->refresh();
    }

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return SettingsShippingCost::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('min_value_threshold')
            ->add('min_value_threshold_formatted', fn($model) => number_format($model->min_value_threshold, 2, ',', '.') . ' €')
            ->add('max_value_threshold')
            ->add('max_value_threshold_formatted', fn($model) => number_format($model->max_value_threshold, 2, ',', '.') . ' €')
            ->add('shipping_cost')
            ->add('shipping_cost_formatted', fn($model) => number_format($model->shipping_cost, 2, ',', '.') . ' €');
    }

    public function columns(): array
    {
        return [
            Column::add()
                ->title('Minimum Threshold')
                ->field('min_value_threshold_formatted', 'min_value_threshold')  // Add the actual DB field
                ->sortable()
                ->searchable(),

            Column::add()
                ->title('Maximum Threshold')
                ->field('max_value_threshold_formatted', 'max_value_threshold')  // Add the actual DB field
                ->sortable()
                ->searchable(),

            Column::add()
                ->title('Shipping Cost')
                ->field('shipping_cost_formatted', 'shipping_cost')  // Add the actual DB field
                ->sortable()
                ->searchable(),

            Column::action('Actions')
        ];
    }


    #[On('edit')]
    public function edit($rowId): void
    {
        $this->dispatch('openModal', component: 'edit-shipping-cost', arguments: ['id' => $rowId]);
    }

    #[On('delete')]
    public function delete($rowId): void
    {
        SettingsShippingCost::findOrFail($rowId)->delete();
        Toaster::success('Shipping cost deleted successfully!');
        $this->dispatch('shippingCostChanged');
    }


    public function actions(SettingsShippingCost $row): array
    {
        return [
            Button::add('edit')
                ->slot(Blade::render('<flux:button icon="pencil" class="cursor-pointer" wire:click="$dispatch(\'editShippingCost\', { id: ' . $row->id . ' })">Edit</flux:button>'))
                ->id(),


            Button::add('delete')
                ->slot(Blade::render('<flux:button icon="trash" class="cursor-pointer" variant="danger" wire:click="$dispatch(\'delete\', { rowId: ' . $row->id . '})">Remove</flux:button>'))
                ->id()
            ];


    }
}
