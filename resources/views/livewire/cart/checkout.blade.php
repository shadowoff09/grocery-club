<div>
    <!-- Cart Items Section -->
    <div class="cart-items">
        @foreach ($cartItems as $item)
            <div class="cart-item">
                <div class="product-info">
                    <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}">
                    <h3>{{ $item['product']->name }}</h3>
                </div>
                <div class="quantity">{{ $item['quantity'] }}</div>
                <div class="price">
                    @if ($item['showDiscount'])
                        <span class="original-price">{{ $item['originalTotal'] }}€</span>
                        <span class="discounted-price">{{ $item['total'] }}€</span>
                        <span class="discount-badge">-{{ $item['discount'] }}%</span>
                    @else
                        <span>{{ $item['total'] }}€</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Checkout Summary Section -->
    <div class="checkout-summary">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>{{ $total }}€</span>
        </div>
        
        @if ($totalDiscount > 0)
        <div class="summary-row discount">
            <span>Discount:</span>
            <span>-{{ $totalDiscount }}€</span>
        </div>
        @endif
        
        <div class="summary-row shipping">
            <span>Shipping:</span>
            <span>{{ $shippingCost }}€</span>
        </div>
        
        @if ($shippingCost > 0 && isset($minThresholdSoShippingIsFree))
        <div class="free-shipping-note">
            Add {{ $minThresholdSoShippingIsFree - $total }}€ more to get free shipping
        </div>
        @endif
        
        <div class="summary-row total">
            <span>Total:</span>
            <span>{{ $totalWithShipping }}€</span>
        </div>
    </div>
</div> 