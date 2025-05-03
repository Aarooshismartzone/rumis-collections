@php
    $cat = $product->category;
@endphp

<div class="col-xl-3 col-lg-4 col-sm-6 col-12 px-1">
    <div class="card border-0 product-card">
        <a href="{{ route('frontend.viewproduct', ['product_slug' => $product->product_slug]) }}"
            style="color: inherit; text-decoration: none;" target="_blank">
            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="z-index: 10"
                alt="{{ $product->product_name }}">
        </a>
        <div class="card-body text-center">
            <a href="{{ route('frontend.viewproduct', ['product_slug' => $product->product_slug]) }}"
                style="color: inherit; text-decoration: none;" target="_blank">
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
            </a>
            @if ($cat->is_productsize)
                <button class="atc-btn" data-bs-toggle="modal" data-bs-target="#sizeModal{{ $product->id }}">
                    Add To Cart
                </button>

                <!-- Modal -->
                <div class="modal fade" id="sizeModal{{ $product->id }}" tabindex="-1"
                    aria-labelledby="sizeModalLabel{{ $product->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Select Size</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                @php
                                    $sizes = explode(',', $product->product_size);
                                @endphp
                                @foreach ($sizes as $size)
                                    <a href="{{ route('frontend.addtocart', ['product_slug' => $product->product_slug, 'product_size' => trim($size)]) }}"
                                        class="btn btn-outline-primary m-1">{{ trim($size) }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('frontend.addtocart', ['product_slug' => $product->product_slug]) }}"
                    class="atc-btn">Add To
                    Cart</a>
            @endif
        </div>
    </div>
</div>
