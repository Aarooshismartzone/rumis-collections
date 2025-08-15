@extends('backend.layouts.master')

@section('title')
    {{ isset($category) ? 'Edit Category' : 'Add a Product Category' }}
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">{{ isset($category) ? 'Edit Category' : 'Add a Product Category' }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('backend.categories.save', $category->id ?? null) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            {{-- Category Name --}}
                            <div class="mb-3">
                                <label for="category_name" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="category_name" name="category_name"
                                    placeholder="Enter category name"
                                    value="{{ old('category_name', $category->category_name ?? '') }}" required>
                            </div>

                            {{-- Parent Category --}}
                            <div class="mb-3">
                                <label for="parent_category" class="form-label">Parent Category</label>
                                <select class="form-select" id="parent_category" name="parent_category">
                                    <option value="">Uncategorized</option>
                                    @foreach ($categories as $parent)
                                        <option value="{{ $parent->id }}"
                                            {{ isset($category) && $category->parent_category == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Category Image --}}
                            <div class="mb-3">
                                <label for="category_image" class="form-label">Category Image</label>
                                <input type="file" class="form-control" id="category_image" name="category_image"
                                    accept="image/*">

                                @if (isset($category) && $category->category_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $category->category_image) }}" alt="Category Image"
                                            class="img-thumbnail" width="120">
                                    </div>
                                @endif
                            </div>

                            {{-- Submit Button --}}
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ isset($category) ? 'Update Category' : 'Add Category' }}
                                </button>
                                <a href="{{ route('backend.categories.view') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
