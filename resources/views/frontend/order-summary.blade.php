<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" href="{{ asset('css/frontend/navbar-black.css') }}">
    <title>Order Summary</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .summary-box {
            border: 1px solid #dee2e6;
            padding: 20px;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    @include('frontend.layouts.partials.navbar-black')

    <div class="container my-5">
        <h3 class="mb-4">Order Summary</h3>

        <div class="summary-box">
            <h5>Customer Details</h5>
            <p><strong>Name:</strong> {{ Session::get('customer_name') ?? 'Guest' }}</p>
            <p><strong>Email:</strong> {{ Session::get('customer_email') ?? 'N/A' }}</p>
            {{-- <p><strong>Phone:</strong> {{ $summary['phone'] ?? 'N/A' }}</p> --}}

            <hr>

            <h5>Delivery Address</h5>
            <p>{{ $summary['delivery']['address_line_1'] }}, {{ $summary['delivery']['address_line_2'] ?? '' }}</p>
            <p>{{ $summary['delivery']['city'] }}, {{ $summary['delivery']['state'] }}</p>
            <p>{{ $summary['delivery']['country'] }} - {{ $summary['delivery']['pin_code'] }}</p>
            <p>Contact Number: {{ $summary['delivery']['pnum'] }}</p>

            <hr>
            <h5>Billing Address</h5>
            <p>{{ $summary['billing']['address_line_1'] }}, {{ $summary['billing']['address_line_2'] ?? '' }}</p>
            <p>{{ $summary['billing']['city'] }}, {{ $summary['billing']['state'] }}</p>
            <p>{{ $summary['billing']['country'] }} - {{ $summary['billing']['pin_code'] }}</p>
            <p>Contact Number: {{ $summary['billing']['pnum'] }}</p>

            <hr>

            <h5>Order Details</h5>
            <p><strong>Total:</strong> ₹{{ number_format($summary['total_price'], 2) }}</p>
            <p><strong>Delivery:</strong> ₹{{ number_format($summary['delivery_charge'], 2) }}</p>
            @if ($summary['gst_applicable'] ?? false)
                <p><strong>GST:</strong> ₹{{ number_format($summary['gst_amount'], 2) }}</p>
            @endif
            <h4 class="mt-3 text-success">Grand Total: ₹{{ number_format($summary['grand_total'], 2) }}</h4>
        </div>

        <div class="text-end mt-4">
            <a href="{{ route('payment') }}" class="btn btn-primary px-4 py-2">Proceed to Make Payment</a>
        </div>
    </div>
    @include('frontend.layouts.partials.footer')
</body>

</html>
