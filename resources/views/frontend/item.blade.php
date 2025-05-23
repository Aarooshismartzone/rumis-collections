<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend/navbar-black.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend/productpage.css') }}">
    <title>{{ $product->product_name ?? 'Product Details' }}</title>
    <style>
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            font-weight: bold;
            background-color: rgba(0, 0, 0, 0.747);
            padding: 5px
        }
    </style>
</head>

<body style="font-family: 'Poppins', serif">
    <main>
        @include('frontend.layouts.partials.navbar-black')
        {{-- hero section --}}
        @if (session()->has('msg'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('msg') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        <div class="container">
            <div class="row mt-4">
                <div class="col-lg-4 col-md-5 col-12 px-2">
                    @include('frontend.layouts.product-image-carousel')
                </div>
                <div class="col-lg-8 col-md-7 col-12 px-2">
                    <h1 class="product-title">{{ $product->product_name }}</h1>
                    <p class="mt-2 product-description">{{ $product->description }}</p>
                    <hr>
                    <p class="product-price">
                        ₹{{ $product->discounted_price }} <span
                            class="actual-price">₹{{ $product->actual_price }}</span>
                    </p>
                    @if ($product->category->is_productsize)
                        <div class="mt-3">
                            <h6>Select Size</h6>
                            <div class="btn-group" role="group" aria-label="Product Sizes" id="sizeOptions">
                                @foreach (explode(',', $product->product_size) as $size)
                                    <input type="radio" class="btn-check" name="product_size"
                                        id="size-{{ trim($size) }}" autocomplete="off" value="{{ trim($size) }}">
                                    <label class="btn btn-outline-primary me-1 mb-1"
                                        for="size-{{ trim($size) }}">{{ trim($size) }}</label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="row mt-2">
                        <div class="col-md-6 col-12 p-2">
                            <a href="#" id="addToCartBtn" data-slug="{{ $product->product_slug }}"
                                class="atc-btn disabled">Add To Cart</a>
                        </div>
                        <div class="col-md-6 col-12 p-2">
                            <a href="{{ route('frontend.addtowishlist', ['product_slug' => $product->product_slug]) }}"
                                class="atc-btn">Add To Wish List</a>
                        </div>
                    </div>
                    <h5 class="mt-4 pd-section-title">Product Details</h5>
                    <table class="table table-secondary table-striped">
                        <thead>
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
    </main>

    @include('frontend.layouts.partials.footer')

    <script>
        $(document).ready(function() {
            let sizeRequired = {{ $product->category->is_productsize ? 'true' : 'false' }};

            // Enable button when size is selected
            $('input[name="product_size"]').on('change', function() {
                $('#addToCartBtn').removeClass('disabled');
            });

            // On Add to Cart Click
            $('#addToCartBtn').on('click', function(e) {
                e.preventDefault();

                let slug = $(this).data('slug');
                let url = '/product/add-to-cart/' + slug;

                if (sizeRequired) {
                    let selectedSize = $('input[name="product_size"]:checked').val();
                    if (!selectedSize) {
                        alert('Please select a size before adding to cart.');
                        return;
                    }
                    url += '/' + encodeURIComponent(selectedSize);
                }

                window.location.href = url;
            });
        });
    </script>

</body>

</html>
