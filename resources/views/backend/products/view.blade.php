@extends('backend.layouts.master')

@section('title')
    Products
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('backend.products.add') }}" class="btn btn-primary mb-3">Add Product</a>

    <table id="myTableProducts" class="table table-bordered">
        <thead>
            <tr>
                <th>SN</th>
                <th>ID</th>
                <th>Image</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Actual Price</th>
                <th>Discounted Price</th>
                <th>Stock</th>
                <th>Featured</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $key => $product)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $product->id }}</td>
                    <td><img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" width="180"
                            height="auto"></td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->category->category_name ?? 'Uncategorized' }}</td>
                    <td>₹{{ number_format($product->actual_price, 2) }}</td>
                    <td>₹{{ number_format($product->discounted_price, 2) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->is_featured ? 'Yes' : 'No' }}</td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('backend.products.add', $product->id) }}"
                                style="color: green; text-decoration: none;"><i class="fa-solid fa-pen-to-square"></i></a>
                            <form action="{{ route('backend.products.delete', $product->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <button type="submit" id="delete-btn-{{ $product->id }}" style="display:none;"></button>

                                <p style="color: red; margin-left: 10px; cursor: pointer;"
                                    onclick="if(confirm('Are you sure?')) { $('#delete-btn-{{ $product->id }}').click(); }">
                                    <i class="fa-solid fa-trash"></i>
                                </p>
                            </form>
                            <a href="{{ route('frontend.viewproduct', $product->product_slug) }}" target="_blank"
                                style="color: purple; text-decoration: none; margin-left: 10px;"><i
                                    class="fa-solid fa-eye"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#myTableProducts').DataTable({
                dom: 'Bfrtip',
                buttons: ['excel', 'print']
            });
        });
    </script>
@endsection
