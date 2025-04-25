<!-- Isto o componente livewire que mostra a quantidade de itens que temos no cart -->
<div class="relative">
    @if($cartCount > 0)
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
            {{ $cartCount }}
        </span>
    @endif
    <flux:icon name="shopping-cart" class="w-5 h-5" />
</div>
