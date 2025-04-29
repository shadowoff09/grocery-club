<?php

namespace App\Livewire;

use App\Models\SettingsShippingCost;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class ShippingCostsTable extends PowerGridComponent

{
    public string $tableName = 'shipping-costs-table';

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
    }

    public function actions(SettingsShippingCost $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                ->id()
                ->class('inline-flex items-center justify-center gap-2
                    rounded-lg px-5 py-2
                    bg-zinc-300 text-black
                    dark:bg-zinc-900 dark:text-white
                    hover:bg-zinc-400 dark:hover:bg-zinc-800
                    hover:shadow-md transition-all duration-200
                    focus:outline-none focus:ring-2 focus:ring-offset-2
                    focus:ring-blue-500 dark:focus:ring-blue-400
                    cursor-pointer')
                ->dispatch('editShippingCost', ['id' => $row->id]),



            Button::add('delete')
                ->slot('Remove')
                ->id()
                ->class('inline-flex items-center justify-center gap-2
       rounded-lg px-5 py-2
       bg-red-300 text-black
       dark:bg-red-900 dark:text-white
       hover:bg-red-400 dark:hover:bg-red-800
       hover:shadow-md transition-all duration-200
       focus:outline-none focus:ring-2 focus:ring-offset-2
       focus:ring-red-500 dark:focus:ring-red-400
       cursor-pointer')
                ->dispatch('delete', ['rowId' => $row->id])
                ->confirm('Tem certeza que deseja remover este custo de envio?'),
        ];
    }
}
