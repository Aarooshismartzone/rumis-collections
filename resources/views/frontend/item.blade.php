<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend/navbar-black.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend/productpage.css') }}">
    <title>{{ $product->product_name ?? 'Product Details' }}</title>
</head>

<body style="font-family: 'Poppins', serif">
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
                    ₹{{ $product->discounted_price }} <span class="actual-price">₹{{ $product->actual_price }}</span>
                </p>
                <div class="row mt-2">
                    <div class="col-md-6 col-12 p-2">
                        <a href="{{ route('frontend.addtocart', ['product_slug' => $product->product_slug]) }}"
                            class="atc-btn">Add To Cart</a>
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
</body>

</html>
