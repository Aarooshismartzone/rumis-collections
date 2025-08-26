<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" href="{{ asset('css/frontend/navbar-black.css') }}">
    <title>Shipping - Rumi's Collections</title>
</head>

<body>
    {{-- Navbar --}}
    @include('frontend.layouts.partials.navbar-black')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="mb-4 text-center fw-bold">Shipping Policy</h2>

                        <p>
                            At <strong>Rumis's Collections</strong>, we aim to deliver your products in a safe and
                            timely manner.
                            Please read our shipping policy carefully to understand how your orders will be processed
                            and delivered.
                        </p>

                        <h5 class="mt-4 fw-semibold">Shipping Method</h5>
                        <p>
                            Our primary shipping method is via <strong>Postal Registry</strong>.
                            In case this option is not available, we may use alternative reliable shipping services to
                            ensure delivery.
                        </p>

                        <h5 class="mt-4 fw-semibold">Loss or Damage in Transit</h5>
                        <p>
                            In the rare event that your order is lost in transit, we will either:
                        </p>
                        <ul>
                            <li>Send a replacement product, or</li>
                            <li>Provide a complete refund (limited to the total amount paid for that product).</li>
                        </ul>
                        <p class="text-muted">
                            Please note: Customers are not entitled to claim any additional compensation beyond the
                            product value.
                        </p>

                        <h5 class="mt-4 fw-semibold">Delivery Charges</h5>
                        <p>
                            The delivery charge applicable to your order will be exactly as shown on your bill at
                            checkout,
                            regardless of the actual shipping cost charged by the courier service.
                        </p>

                        <h5 class="mt-4 fw-semibold">Delivery Area</h5>
                        <p>
                            Currently, we are only able to deliver orders within <strong>India</strong>.
                            International delivery is not available at the moment.
                        </p>

                        <div class="alert alert-info mt-4 rounded-3">
                            <i class="bi bi-info-circle me-2"></i>
                            If you have any questions regarding shipping, please contact our support team.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('frontend.layouts.partials.footer')
</body>

</html>
