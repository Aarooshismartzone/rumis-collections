<div class="col-xl-3 col-lg-4 col-sm-6 col-12 px-1">
    <a href="{{ route('frontend.viewproduct', ['product_slug' => $product->product_slug]) }}"
        style="color: inherit; text-decoration: none;" target="_blank">
        <div class="card border-0 product-card">
            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="z-index: 10"
                alt="{{ $product->product_name }}">
            <div class="card-body text-center">
                <h6 class="card-title text-truncate" style="text-transform: uppercase">
                    {{ $product->product_name }}</h6>
                @if ($product->discounted_price)
                    <p class="fw-bold" style="color: green">
                        ₹{{ number_format($product->discounted_price, 2) }}
                        <span
                            class="text-muted text-decoration-line-through">₹{{ number_format($product->actual_price, 2) }}</span>
                    </p>
                @else
                    <p class="fw-bold">${{ number_format($product->actual_price, 2) }}</p>
                @endif
                <a href="{{ route('frontend.addtocart', ['product_slug' => $product->product_slug]) }}"
                    class="atc-btn">Add To Cart</a>
            </div>
        </div>
    </a>
</div>
