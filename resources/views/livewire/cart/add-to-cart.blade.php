<div class="flex items-center space-x-2">
    <div class="flex items-center border rounded-lg mr-6">
        <button
            wire:click="decrement"
            class="px-3 py-1 border-r hover:bg-gray-100 dark:hover:bg-zinc-700">
            -
        </button>
        <span class="px-3 py-1">{{ $quantity }}</span>
        <button
            wire:click="increment"
            class="px-3 py-1 border-l hover:bg-gray-100 dark:hover:bg-zinc-700">
            +
        </button>
    </div>
    <button
        wire:click="addToCart"
        class="bg-zinc-900 text-white px-4 py-2 rounded hover:bg-zinc-700 cursor-pointer">
        Add to Cart
    </button>
</div>
