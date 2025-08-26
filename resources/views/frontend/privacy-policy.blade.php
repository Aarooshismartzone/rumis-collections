<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" href="{{ asset('css/frontend/navbar-black.css') }}">
    <title>Privacy Policy | Rumi's Collections</title>
</head>

<body>
    {{-- Navbar --}}
    @include('frontend.layouts.partials.navbar-black')

    <main class="container py-5">
        <h1 class="mb-4">Privacy Policy</h1>
        <p>At Rumi's Collections, we value your privacy and are committed to protecting your personal information. This
            policy explains how we collect, use, and safeguard your data when you visit our website or make a purchase.
        </p>

        <h3>Information We Collect</h3>
        <ul>
            <li>Personal details such as name, email address, phone number, and shipping address.</li>
            <li>Payment details for processing your orders.</li>
            <li>Browsing data such as IP address and cookies for improving your experience.</li>
        </ul>

        <h3>How We Use Your Information</h3>
        <ul>
            <li>To process and deliver your orders.</li>
            <li>To improve our products and services.</li>
            <li>To send order updates, offers, and promotions (with your consent).</li>
        </ul>

        <h3>Data Security</h3>
        <p>We implement security measures to protect your information. However, no online method is 100% secure, and we
            cannot guarantee absolute protection.</p>

        <h3>Contact Us</h3>
        <p>If you have questions about our Privacy Policy, please contact us at <a
                href="mailto:contact@rumiscollections.com">contact@rumiscollections.com</a>.</p>
    </main>

    @include('frontend.layouts.partials.footer')
</body>

</html>
