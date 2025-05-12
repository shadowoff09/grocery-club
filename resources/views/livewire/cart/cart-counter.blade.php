<!-- Isto o componente livewire que mostra a quantidade de itens que temos no cart -->
<div class="relative inline-flex">
    @if($cartCount > 0)
        <span class="absolute -top-2 -right-2 min-w-[1.25rem] h-5 px-1 bg-emerald-600 text-white text-xs font-medium rounded-full flex items-center justify-center">
            {{ $cartCount }}
        </span>
    @endif
    <x-lucide-shopping-cart class="w-5 h-5" />
</div>
