<x-layouts.app :title="__('Request Product Restock')" :breadcrumbs="['Inventory Management', 'Request Restock']">
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold mb-2 text-black dark:text-white">{{ __('Request Product Restock') }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Select products that need restocking and specify quantities') }}</p>
            </div>
            <flux:button 
                icon="arrow-left" 
                variant="outline" 
                href="{{ route('employee.inventory.index') }}"
                class="cursor-pointer"
            >
                Back to Inventory
            </flux:button>
        </div>
        
        <livewire:employee.request-restock />
    </div>
</x-layouts.app> 