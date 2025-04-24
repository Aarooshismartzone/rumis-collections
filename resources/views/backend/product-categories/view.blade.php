@extends('backend.layouts.master')

@section('title')
    Categories
@endsection

@section('content')
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Product Categories</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <a href="{{ route('backend.categories.add') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Category
                    </a>
                </div>

                <table id="categoryTable" class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Parent Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $index => $cat)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $cat->id }}</td>
                                <td>{{ $cat->category_name }}</td>
                                <td>{{ $cat->parent_category ? $cat->parent->category_name : 'Uncategorized' }}</td>
                                <td>
                                    <a href="{{ route('backend.categories.add', $cat->id) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('backend.categories.delete', $cat->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Are you sure you wish to delete the category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#categoryTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['excel', 'print']
            });
        });
    </script>
@endsection
