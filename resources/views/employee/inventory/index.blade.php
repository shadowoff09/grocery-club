<x-layouts.app :title="__('Inventory Management')">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-black dark:text-white">{{ __('Inventory Management') }}</h1>
        
        <livewire:employee.inventory />
    </div>
</x-layouts.app> 