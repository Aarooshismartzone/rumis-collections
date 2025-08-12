<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" href="{{ asset('css/frontend/navbar-black.css') }}">
    <link rel="stylesheet" href="{{ asset('css/frontend/productpage.css') }}">
    <title>{{ $product->product_name ?? 'Product Details' }}</title>
    <style>
        /* Visual "disabled" state that remains clickable (so JS can react and show modal) */
        .btn-disabled {
            opacity: 0.65;
            cursor: not-allowed;
            pointer-events: auto;
            /* ensure clicks are allowed so we can show the modal */
        }

        /* "ready" state when size is selected */
        .btn-ready {
            opacity: 1;
            cursor: pointer;
        }
    </style>
</head>

<body style="font-family: 'Poppins', sans-serif; background-color: #f8f9fa;">
    <main>
        @include('frontend.layouts.partials.navbar-black')

        {{-- Success Message --}}
        @if (session()->has('msg'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    {{ session('msg') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        {{-- Product Section --}}
        <div class="container py-5">
            <div class="row g-4">
                {{-- Image Section --}}
                <div class="col-lg-5 col-md-6 col-12">
                    <div class="card border-0 shadow-sm">
                        @include('frontend.layouts.product-image-carousel')
                    </div>
                </div>

                {{-- Info Section --}}
                <div class="col-lg-7 col-md-6 col-12">
                    <h1 class="product-title">{{ $product->product_name }}</h1>
                    <p class="product-description mt-3">{{ $product->description }}</p>

                    <hr class="my-3">

                    {{-- Price --}}
                    <p class="product-price">
                        ₹{{ number_format($product->discounted_price, 2) }}
                        <span class="actual-price ms-2">₹{{ number_format($product->actual_price, 2) }}</span>
                    </p>

                    {{-- Sizes --}}
                    @if ($product->category->is_productsize)
                        <div class="mt-4">
                            <h6 class="fw-semibold">Select Size</h6>
                            <div class="btn-group flex-wrap mt-2" role="group" id="sizeOptions">
                                @foreach (explode(',', $product->product_size) as $size)
                                    <input type="radio" class="btn-check" name="product_size"
                                        id="size-{{ trim($size) }}" autocomplete="off" value="{{ trim($size) }}">
                                    <label class="btn btn-outline-dark me-2 mb-2"
                                        for="size-{{ trim($size) }}">{{ trim($size) }}</label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="row mt-4 g-3">
                        <div class="col-md-6 col-12">
                            <button type="button" id="addToCartBtn" data-slug="{{ $product->product_slug }}"
                                class="btn btn-dark w-100 py-2 btn-disabled" aria-disabled="true">
                                <i class="fa-solid fa-cart-plus me-2"></i> Add To Cart
                            </button>
                        </div>
                        <div class="col-md-6 col-12">
                            <a href="{{ route('frontend.addtowishlist', ['product_slug' => $product->product_slug]) }}"
                                class="btn btn-outline-secondary w-100 py-2">
                                <i class="fa-regular fa-heart me-2"></i> Add To Wishlist
                            </a>
                        </div>
                    </div>

                    {{-- Product Details --}}
                    <div class="mt-5">
                        <h5 class="pd-section-title">Product Details</h5>
                        <div class="table-responsive mt-3">
                            <table class="table table-hover table-bordered align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Property</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productinfos as $productinfo)
                                        <tr>
                                            <td>{{ $productinfo->property }}</td>
                                            <td>{{ $productinfo->value }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    {{-- Size Selection Modal --}}
    <div class="modal fade" id="sizeModal" tabindex="-1" aria-labelledby="sizeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="sizeModalLabel"><i class="fa-solid fa-ruler-combined me-2"></i> Size
                        Required</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    Please select a size before adding this product to your cart.
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-dark px-4" data-bs-dismiss="modal">Okay</button>
                </div>
            </div>
        </div>
    </div>

    @include('frontend.layouts.partials.footer')

    <script>
        $(document).ready(function() {
            // true/false from backend
            let sizeRequired = {{ $product->category->is_productsize ? 'true' : 'false' }};
            const $addBtn = $('#addToCartBtn');

            // If size is NOT required, make the button ready immediately.
            if (!sizeRequired) {
                $addBtn.removeClass('btn-disabled').addClass('btn-ready').attr('aria-disabled', 'false');
            } else {
                // ensure aria-disabled true initially
                $addBtn.attr('aria-disabled', 'true');
            }

            // When user selects a size — make button visually active
            $('input[name="product_size"]').on('change', function() {
                $addBtn.removeClass('btn-disabled').addClass('btn-ready').attr('aria-disabled', 'false');
            });

            // Click handler (always active so we can show modal if needed)
            $addBtn.on('click', function(e) {
                e.preventDefault();
                let slug = $(this).data('slug');
                let url = '/product/add-to-cart/' + slug;

                if (sizeRequired) {
                    let selectedSize = $('input[name="product_size"]:checked').val();
                    if (!selectedSize) {
                        // show bootstrap modal
                        let sizeModalEl = document.getElementById('sizeModal');
                        if (typeof bootstrap !== 'undefined' && sizeModalEl) {
                            // getOrCreate instance (safe if modal already created)
                            let sizeModal = bootstrap.Modal.getOrCreateInstance(sizeModalEl);
                            sizeModal.show();
                        } else {
                            // fallback
                            alert('Please select a size before adding to cart.');
                        }
                        return;
                    }
                    url += '/' + encodeURIComponent(selectedSize);
                }

                // proceed to add-to-cart
                window.location.href = url;
            });
        });
    </script>

</body>

</html>
