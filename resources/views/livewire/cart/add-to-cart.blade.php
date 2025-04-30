<div class="w-full flex items-center justify-between">
    <!-- Counter -->
    <div
        class="flex items-center rounded-xl overflow-hidden border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm">
        <button
            wire:click="decrement"
            class="w-10 h-10 flex items-center justify-center text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition duration-150 cursor-pointer">
            &minus;
        </button>
        <span class="w-12 text-center text-lg font-semibold text-zinc-800 dark:text-zinc-100">
            {{ $quantity }}
        </span>
        <button
            wire:click="increment"
            class="w-10 h-10 flex items-center justify-center text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition duration-150 cursor-pointer">
            +
        </button>
    </div>

    <!-- Add to Cart Button -->
    <button
        wire:click="addToCart"
        class="cursor-pointer inline-flex items-center gap-2 px-5 py-2.5 bg-zinc-900 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200 hover:bg-zinc-700 active:scale-95 dark:bg-zinc-200 dark:text-zinc-900 dark:hover:bg-zinc-100">
        <x-lucide-shopping-cart class="w-4 h-4" />
        Add to Cart
    </button>
</div>
