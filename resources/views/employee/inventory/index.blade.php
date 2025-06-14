<x-layouts.app :title="__('Inventory Management')" :breadcrumbs="['Inventory Management']">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-2 text-black dark:text-white">{{ __('Inventory Management') }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Monitor your product stock levels') }}</p>
        <livewire:employee.inventory />
    </div>
</x-layouts.app> 