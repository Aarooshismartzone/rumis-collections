@extends('backend.layouts.master')

@section('title')
    Products
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">All Products</h4>
            <div>
                <a href="{{ route('backend.products.add') }}" class="btn btn-light btn-sm me-2">
                    <i class="fas fa-plus"></i> Add Product
                </a>
                <button class="btn btn-light btn-sm" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>

        <div class="card-body">
            <table id="productsTable" class="table table-hover align-middle table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
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
                            <td>
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}"
                                    class="img-thumbnail" style="max-width: 80px;">
                            </td>
                            <td>{{ $product->product_name }}</td>
                            <td>
                                @if ($product->category)
                                    <span class="badge bg-info text-dark">{{ $product->category->category_name }}</span>
                                @else
                                    <span class="badge bg-secondary">Uncategorized</span>
                                @endif
                            </td>
                            <td>₹{{ number_format($product->actual_price, 2) }}</td>
                            <td>₹{{ number_format($product->discounted_price, 2) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                @if ($product->is_featured)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-warning text-dark">No</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('backend.products.add', $product->id) }}"
                                        class="btn btn-sm btn-outline-success" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('backend.products.delete', $product->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('frontend.viewproduct', $product->product_slug) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#productsTable').DataTable({
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
