@extends('backend.layouts.master')

@section('title')
    Order #{{ $order->id }} Details
@endsection

@section('content')
    <div class="mb-3">
        <a href="{{ route('backend.orders.view') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Order Information</h5>
        </div>
        <div class="card-body">
            <p><strong>Order ID:</strong> {{ $order->id }}</p>
            <p><strong>Customer:</strong>
                {{ $customer ? $customer->fname . ' ' . $customer->lname : 'Guest' }}
            </p>
            <p><strong>{{ $customer ? 'Customer ID' : 'Guest Token' }}:</strong>
                {{ $customer ? $order->customer_id : $order->guest_token }}
            </p>
            <p><strong>Order Status:</strong>
                <span class="badge bg-info">{{ ucfirst($order->order_status) }}</span>
            </p>
            <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
            <p><strong>Grand Total:</strong> ₹{{ number_format($order->grand_total, 2) }}</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Order Items</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Price (₹)</th>
                        <th>Total (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderitems as $item)
                        @php
                            $product = \App\Models\Product::find($item->product_id);
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $item->product_size }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($product->discounted_price, 2) }}</td>
                            <td>{{ number_format($item->quantity * $product->discounted_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mb-4">
        <a href="{{ route('receipt.download', ['oid' => $order->id]) }}" class="btn btn-success">
            <i class="fas fa-file-pdf"></i> Download PDF Receipt
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Order Notes</h5>
        </div>
        <div class="card-body">
            @if ($notes->count())
                @foreach ($notes as $note)
                    <div class="border rounded p-2 mb-2 bg-light">
                        <p class="mb-1">{{ $note->note }}</p>
                        <small class="text-muted">
                            {{ $note->is_manual ? 'Added by User ID: ' . $note->user_id : 'Automated' }}
                            | {{ $note->created_at->format('d M Y H:i') }}
                        </small>
                    </div>
                @endforeach
            @else
                <p class="text-muted">No notes for this order yet.</p>
            @endif

            <form method="POST" action="{{ route('backend.orders.addnote') }}" class="mt-3">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <div class="mb-2">
                    <textarea name="note" placeholder="Add a note..." class="form-control" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Note
                </button>
            </form>
        </div>
    </div>
@endsection
