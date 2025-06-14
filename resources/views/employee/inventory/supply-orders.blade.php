<x-layouts.app :title="__('Supply Orders Management')" :breadcrumbs="['Inventory Management', 'Supply Orders']">
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold mb-2 text-black dark:text-white">{{ __('Supply Orders Management') }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Review and complete supply orders to update inventory stock') }}</p>
            </div>
            <div class="flex gap-3">
                <flux:button 
                    icon="arrow-left" 
                    variant="outline" 
                    href="{{ route('employee.inventory.index') }}"
                    class="cursor-pointer"
                >
                    Back to Inventory
                </flux:button>
                
                <flux:button 
                    icon="plus" 
                    variant="primary" 
                    href="{{ route('employee.inventory.restock') }}"
                    class="cursor-pointer"
                >
                    Request Restock
                </flux:button>
            </div>
        </div>
        
        <livewire:employee.supply-orders />
    </div>
</x-layouts.app> 