<x-layouts.app :title="__('Order Management')">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-black dark:text-white">{{ __('Order Management') }}</h1>
        
        <livewire:employee.orders />
    </div>
</x-layouts.app> 