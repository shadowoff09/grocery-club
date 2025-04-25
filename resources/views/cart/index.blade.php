<x-layouts.app.header :title="__('Cart')">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-black dark:text-white">{{ __('Shopping Cart') }}</h1>

        <livewire:shopping-cart />
    </div>
</x-layouts.app.header>
