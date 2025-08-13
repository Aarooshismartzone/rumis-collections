<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" href="{{ asset('css/frontend/navbar-black.css') }}">
    <title>Cancellation Policy - Rumi's Collections</title>
</head>

<body>
    {{-- Navbar --}}
    @include('frontend.layouts.partials.navbar-black')

    <main class="container py-5">
        <h1 class="mb-4">Cancellation Policy</h1>
        <p>We understand that plans can change, and you may need to cancel your order. Below are our cancellation
            guidelines:</p>

        <h3>Before Shipping</h3>
        <p>You can cancel your order at any time before it is shipped for a full refund.</p>

        <h3>After Shipping</h3>
        <p>Once the order has been shipped, it cannot be cancelled. However, you may return the product within
            <strong>14 days</strong> of delivery in accordance with our <a href="{{ url('/refund-policy') }}">Refund
                Policy</a>.</p>

        <h3>How to Cancel</h3>
        <p>Please contact us at <a href="mailto:contact@rumiscollections.com">contact@rumiscollections.com</a> with your
            order number as soon as possible to request cancellation.</p>
    </main>

    @include('frontend.layouts.partials.footer')
</body>

</html>
