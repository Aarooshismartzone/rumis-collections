<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" href="{{ asset('css/frontend/navbar-black.css') }}">
    <title>Refund Policy - Rumi's Collections</title>
</head>

<body>
    {{-- Navbar --}}
    @include('frontend.layouts.partials.navbar-black')

    <main class="container py-5">
        <h1 class="mb-4">Refund Policy</h1>
        <p>We want you to be completely satisfied with your purchase from Rumi's Collections. If for any reason you are
            not happy with your order, you may request a return or refund under the following terms:</p>

        <h3>Return Period</h3>
        <p>You can return your product within <strong>14 days</strong> from the date of delivery.</p>

        <h3>Eligibility for Refund</h3>
        <ul>
            <li>The item must be unused, in its original packaging, and in the same condition you received it.</li>
            <li>Proof of purchase (invoice or order number) is required.</li>
            <li>Certain items like custom-made or perishable goods are non-refundable.</li>
        </ul>

        <h3>Refund Process</h3>
        <p>Once we receive and inspect your return, we will notify you via email. Approved refunds will be processed to
            your original payment method within 7-10 business days.</p>

        <h3>Contact Us</h3>
        <p>To initiate a return or refund, contact our support team at <a
                href="mailto:contact@rumiscollections.com">contact@rumiscollections.com</a>.</p>
    </main>

    @include('frontend.layouts.partials.footer')
</body>

</html>
