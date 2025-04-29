<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend/navbar-black.css') }}">
    <title>Shopping Cart - {{ $generics['title'] ?? 'Site Title' }}</title>
    <style>
        .empty-cart {
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: bold;
        }
    </style>
</head>

<body style="font-family: 'Poppins', serif">
    @include('frontend.layouts.partials.navbar-black')

    {{-- HERO SECTION --}}

    <div class="section-title pt-4">Cart</div>
    <div class="row mx-4">
        @if ($carts->isEmpty())
            <div class="text-center">
                <h4 class="empty-cart">Your cart is yet to be filled!!</h4>
                <p>Please add products to the cart.</p>
            </div>
        @else
            <div class="col-lg-8 col-md-9 col-12 pe-5">
                <div class="row py-2" style="border-bottom: 2px solid black;">
                    <div class="col-md-3 col-12">
                        Product
                    </div>
                    <div class="col-md-3 col-sm-4 col-6 text-center">
                        Quantity
                    </div>
                    <div class="col-md-3 col-sm-4 col-6">Rate</div>
                    <div class="col-md-3 col-sm-4 col-6">Amount</div>
                </div>
                @foreach ($carts as $cart)
                    <div class="row py-4 cart-item-{{ $cart->id }}" style="border-bottom: 1px solid black;">
                        <div class="col-md-3 col-12">
                            <img src="{{ asset('storage/' . $cart->product->image) }}" width="150" height="auto">
                            <p>{{ $cart->product->product_name }}</p>
                        </div>
                        <div class="col-md-3 col-sm-4 col-6 text-center">
                            <button class="btn btn-sm btn-outline-dark update-quantity"
                                data-cart-id="{{ $cart->id }}" data-action="decrease">-</button>
                            <span class="mx-2 cart-quantity-{{ $cart->id }}">{{ $cart->quantity }}</span>
                            <button class="btn btn-sm btn-outline-dark update-quantity"
                                data-cart-id="{{ $cart->id }}" data-action="increase">+</button>
                        </div>
                        <div class="col-md-3 col-sm-4 col-6">
                            <p>₹{{ $cart->product->discounted_price }}</p>
                        </div>
                        <div class="col-md-3 col-sm-4 col-12">
                            <p class="cart-amount-{{ $cart->id }}" style="color: green; font-weight: bold">
                                ₹{{ number_format($cart->product->discounted_price * $cart->quantity, 2) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-lg-4 col-md-3 col-12 ps-5">
                <div class="py-2 px-4" style="background-color: rgb(231, 231, 231)">
                    <div class="cart-summary">
                        @include('frontend.cart._totals')
                    </div>
                    <div class="text-end mt-3">
                        <a href="{{ route('checkout') }}" class="atc-btn mt-3 d-block text-center">Proceed to
                            checkout</a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        var cartTotalPrice = {{ $totalPrice }};
        var cartUpdateUrl = "{{ route('cart.update') }}";
        var csrfToken = "{{ csrf_token() }}";
        var refreshTotals = "{{ route('cart.refreshTotals') }}";
        var productDiscountedPrice = {{ $cart->product->discounted_price ?? 0 }};
    </script>
    <script src="{{ asset('js/cart.js') }}"></script>

</body>

</html>
