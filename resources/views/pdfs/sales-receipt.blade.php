<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Receipt - Order #{{ $order->id }}</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .header,
        .footer {
            text-align: center;
            margin-top: 10px;
        }

        .logo {
            max-width: 150px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table td,
        table th {
            border-top: 1px solid white;
            border-bottom: 1px solid white;
            border-left: none;
            border-right: none;
        }

        table tr:first-child td,
        table tr:first-child th {
            border-top: none;
        }
    </style>
</head>

<body>

    <div class="header">
        @if (!empty($generics['logo_2']))
            <img src="{{ public_path($generics['logo_2']) }}" alt="Logo" class="logo"><br>
        @endif
        {{-- <h2>{{ $generics['title'] ?? 'Company Name' }}</h2> --}}
        <p>{{ $generics['tagline'] ?? 'Your Trusted Partner' }}</p>
    </div>

    <h2>Receipt - Order #{{ $order->id }}</h2>
    <p><strong>Date:</strong> {{ $date }}</p>

    <h3>Order Summary</h3>
    <p>
        <strong>Order Status:</strong> {{ ucfirst($order->order_status) }} <br>
        <strong>Total:</strong> INR {{ $order->grand_total }} (Includes Delivery + GST) <br>
        <strong>Status:</strong> {{ $payment_status }}<br>
        <strong>Payment Method:</strong> {{ $payment->payment_method ?? 'N/A' }} <br>
        <strong>Transaction ID:</strong> {{ $payment->payment_id ?? 'N/A' }}
    </p>

    <h3>Customer Details</h3>
    @if ($customer_type === 'Registered')
        <p><strong>Customer:</strong> {{ $order->customer->fname ?? 'N/A' }} {{ $order->customer->lname ?? '' }}</p>
    @else
        <p><strong>Guest Token:</strong> {{ $order->guest_token }}</p>
    @endif

    <h3>Items Purchased:</h3>
    <table>
        <thead>
            <tr style="background-color: blue; color: white">
                <th>#</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderItems as $index => $item)
                <tr style="background-color: rgb(219, 255, 250)">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->product_name ?? 'N/A' }}<br><b>Size: {{ $item->product_size }}</b>
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>INR {{ $item->product->discounted_price }}</td>
                    <td>INR {{ $item->quantity * $item->product->discounted_price }}
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td>Total</td>
                <td></td>
                <td></td>
                <td>INR {{ $order->total_amount }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Add: GST amount</td>
                <td></td>
                <td></td>
                <td>INR {{ $order->gst_amount }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Add: Delivery Charges</td>
                <td></td>
                <td></td>
                <td>INR {{ $order->delivery_charge }}</td>
            </tr>
            <tr style="font-weight: bold">
                <td></td>
                <td>Grand Total</td>
                <td></td>
                <td></td>
                <td style="color:green">INR {{ $order->grand_total }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Thank you for your purchase!</p>
    </div>

</body>

</html>
