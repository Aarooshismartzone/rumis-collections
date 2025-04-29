<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend/navbar-black.css') }}">
    <title>Wish List - {{ $generics['title'] ?? 'Site Title' }}</title>
    <style>
        .empty-cart {
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: bold;
        }

        .actual-price {
            color: gray;
            text-decoration: line-through;
        }
    </style>
</head>

<body style="font-family: 'Poppins', serif">
    @include('frontend.layouts.partials.navbar-black')
    @if (session()->has('msg'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('msg') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div class="section-title pt-4">Wish List</div>
    <div class="container">
        @if ($carts->isEmpty())
            <div class="text-center">
                <h4 class="empty-cart">Your Wish List is yet to be filled!!</h4>
                <p>Please add products to the Wish List.</p>
            </div>
        @else
            <div class="row py-2" style="border-bottom: 2px solid black;">
                <div class="col-md-4 col-12">
                    Product
                </div>
                <div class="col-md-4 col-sm-4 col-6">Price</div>
                <div class="col-md-4 col-sm-4 col-6"></div>
            </div>
            @foreach ($carts as $cart)
                <div class="row py-4" style="border-bottom: 1px solid black;">
                    <div class="col-md-4 col-12">
                        <img src="{{ asset('storage/' . $cart->product->image) }}" width="150" height="auto">
                        <p>{{ $cart->product->product_name }}</p>
                    </div>
                    <div class="col-md-4 col-sm-4 col-6">
                        <p><span class="actual-price">₹{{ $cart->product->actual_price }}</span>
                            ₹{{ $cart->product->discounted_price }}</p>
                    </div>
                    <div class="col-md-4 col-sm-4 col-6">
                        <a href="{{ route('frontend.moveToCart', ['id' => $cart->id]) }}">Move To Cart</a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</body>

</html>
