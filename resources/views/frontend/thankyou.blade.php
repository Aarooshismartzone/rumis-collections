<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.layouts.partials.header')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend/navbar-white.css') }}">
    <title>Thank You Page</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .thankyou-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .thankyou-container h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #28a745;
        }

        .thankyou-container p {
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .btn-custom {
            margin: 10px;
            min-width: 180px;
        }
    </style>
</head>

<body>
    @include('frontend.layouts.partials.navbar')

    <div class="thankyou-container">
        <h1>Thank You for Your Purchase!</h1>
        <p>Your payment has been successfully processed. We appreciate your business!</p>
        <div>
            <a href="/" class="btn btn-outline-primary btn-custom">Go to Homepage</a>
            <a href="{{ route('receipt.download', ['oid' => $orderId]) }}" class="btn btn-success btn-custom">Download PDF
                Receipt</a>
        </div>
    </div>
</body>

</html>
