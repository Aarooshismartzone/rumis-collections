<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend/navbar-black.css') }}">
    <title>Shopping Cart - {{ $generics['title'] ?? 'Site Title' }}</title>
    <style>
        body {
            font-family: 'Poppins', serif;
        }

        .empty-cart {
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: bold;
        }

        .cart-item img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .cart-item p {
            margin: 5px 0;
        }

        .cart-summary {
            background-color: rgb(231, 231, 231);
            border-radius: 8px;
            padding: 20px;
        }

        .atc-btn {
            display: inline-block;
            background: #000;
            color: #fff !important;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .atc-btn:hover {
            background: #333;
        }

        /* Responsive Adjustments */
        @media (max-width: 767px) {
            .cart-item {
                border-bottom: 1px solid #ddd;
                padding-bottom: 15px;
                margin-bottom: 15px;
            }

            .cart-item .col-md-3 {
                margin-bottom: 10px;
            }

            .cart-summary {
                margin-top: 20px;
            }

            .cart-quantity-controls {
                display: flex;
                justify-content: center;
                align-items: center;
            }
        }
    </style>
</head>

<body>
    @include('frontend.layouts.partials.navbar-black')

    {{-- HERO SECTION --}}
    <div class="container py-4">
        <h2 class="section-title mb-4">Cart</h2>

        @if ($carts->isEmpty())
            <div class="text-center">
                <h4 class="empty-cart">Your cart is yet to be filled!!</h4>
                <p>Please add products to the cart.</p>
            </div>
        @else
            <div class="row">
                {{-- Cart Items --}}
                <div class="col-lg-8 col-md-7 col-12">
                    {{-- Header (visible only on md and up) --}}
                    <div class="row py-2 d-none d-md-flex fw-bold border-bottom">
                        <div class="col-md-3">Product</div>
                        <div class="col-md-3 text-center">Quantity</div>
                        <div class="col-md-3">Rate</div>
                        <div class="col-md-3">Amount</div>
                    </div>

                    @foreach ($carts as $cart)
                        <div class="row py-3 cart-item cart-item-{{ $cart->id }} align-items-center border-bottom">
                            <div class="col-md-3 col-12 text-center text-md-start">
                                <img src="{{ asset('storage/' . $cart->product->image) }}"
                                    alt="{{ $cart->product->product_name }}">
                                <p class="fw-semibold">{{ $cart->product->product_name }}</p>
                                <p class="text-success fw-bold">Size: {{ $cart->product_size }}</p>
                            </div>

                            <div class="col-md-3 col-sm-4 col-12 cart-quantity-controls text-center my-2 my-md-0">
                                <button class="btn btn-sm btn-outline-dark update-quantity"
                                    data-cart-id="{{ $cart->id }}" data-action="decrease">-</button>
                                <span class="mx-2 cart-quantity-{{ $cart->id }}">{{ $cart->quantity }}</span>
                                <button class="btn btn-sm btn-outline-dark update-quantity"
                                    data-cart-id="{{ $cart->id }}" data-action="increase">+</button>
                            </div>

                            <div class="col-md-3 col-sm-4 col-6 text-center text-md-start">
                                <p>₹{{ $cart->product->discounted_price }}</p>
                            </div>

                            <div class="col-md-3 col-sm-4 col-6 text-center text-md-start">
                                <p class="cart-amount-{{ $cart->id }} text-success fw-bold">
                                    ₹{{ number_format($cart->product->discounted_price * $cart->quantity, 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Cart Summary --}}
                <div class="col-lg-4 col-md-5 col-12 mt-4 mt-md-0">
                    <div class="cart-summary">
                        @include('frontend.cart._totals')
                    </div>
                    <div class="text-end mt-3">
                        <a href="{{ route('checkout') }}" class="atc-btn w-100 text-center">Proceed to checkout</a>
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
