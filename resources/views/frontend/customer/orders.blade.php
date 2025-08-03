@extends('frontend.customer.layouts.customer-master')

@section('title')
    Orders
@endsection

@section('content')
    <h1>Orders</h1>
    <table id="myTableOrders" class="table table-bordered">
        <thead>
            <tr>
                <th>SN</th>
                <th>ID</th>
                <th>Order Date</th>
                <th>Total Price</th>
                <th>Order Status</th>
                <th>See items</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('F j, Y') }}</td>
                    <td>₹{{ $order->grand_total }}</td>
                    <td>{{ $order->order_status }}</td>
                    <td><a href="{{ route('customer.order.items', $order->id) }}">See Items</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        $(document).ready(function() {
            $('#myTableOrders').DataTable({
                dom: 'Bfrtip',
                buttons: ['excel', 'print']
            });
        });
    </script>
@endsection
