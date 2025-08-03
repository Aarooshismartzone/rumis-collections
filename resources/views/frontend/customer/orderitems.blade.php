@extends('frontend.customer.layouts.customer-master')

@section('title')
    Order #{{ $order->id }} Items
@endsection

@section('content')
    <div class="mb-3">
        <a href="{{ route('customer.orders') }}" class="btn btn-secondary">
            ← Back to Orders
        </a>
    </div>

    <h2>Order #{{ $order->id }} - Items</h2>
    <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
    <p><strong>Total Price:</strong> ₹{{ number_format($order->grand_total, 2) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>

    <table id="orderItemsTable" class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>SN</th>
                <th>Image</th>
                <th>Product Name</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Price (each)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderItems as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if ($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                alt="{{ $item->product->product_name }}" style="height: 60px;">
                        @else
                            <em>No image</em>
                        @endif
                    </td>
                    <td>{{ $item->product->product_name ?? 'N/A' }}</td>
                    <td>{{ $item->product_size }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->product->discounted_price ?? 0, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#orderItemsTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['excel', 'print']
            });
        });
    </script>
@endsection
