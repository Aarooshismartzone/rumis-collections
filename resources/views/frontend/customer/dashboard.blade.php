@extends('frontend.customer.layouts.customer-master')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="container">
        <h3>Customer Dashboard</h3>
        <div class="row mt-2">
            <div class="col-md-6 col-12 pe-2">
                <h4>Latest orders</h4>
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th scope="col">SN</th>
                            <th scope="col">Order ID</th>
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
                                <td>{{ $order->total_price }}</td>
                                <td>{{ $order->status }}</td>
                                <td><a href="{{ route('customer.order.items', $order->id) }}">See Items</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-6 col-12 ps-2">
                <h4>Something cool is coming soon</h4>
            </div>
        </div>
    </div>
@endsection
