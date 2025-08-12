@extends('backend.layouts.master')

@section('title')
    Orders
@endsection

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">All Orders</h4>
            <div>
                <button class="btn btn-light btn-sm" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="ordersTable" class="table table-hover align-middle table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Grand Total</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Placed On</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $order)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $order->id }}</td>
                            <td>
                                @if ($order->customer_id)
                                    <span class="badge bg-info text-dark">Customer #{{ $order->customer_id }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">Guest</span>
                                @endif
                            </td>
                            <td>₹{{ number_format($order->grand_total, 2) }}</td>
                            <td>{{ ucfirst($order->payment_method) }}</td>
                            <td>
                                @php
                                    $statusClass = match (strtolower($order->order_status)) {
                                        'pending' => 'bg-warning',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($order->order_status) }}</span>
                            </td>
                            <td>{{ $order->created_at ? $order->created_at->format('d M Y') : '-' }}</td>
                            <td>
                                <a href="{{ route('backend.orders.show', $order->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['excel', 'print'],
                order: [
                    [1, "desc"]
                ],
                pageLength: 10
            });
        });
    </script>
@endsection
