@extends('backend.layouts.master')

@section('title')
    {{ isset($product) ? 'Edit Product' : 'Add Product' }}
@endsection

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-box"></i> {{ isset($product) ? 'Edit Product' : 'Add Product' }}
            </h4>
            <a href="{{ route('backend.products.view') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
        </div>

        <div class="card-body">
            {{-- Display validation errors --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form
                action="{{ isset($product) ? route('backend.products.store', $product->id) : route('backend.products.store', ['product_id' => null]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Product Name --}}
                <div class="mb-3">
                    <label for="product_name" class="form-label fw-bold">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name"
                        placeholder="Enter product name" value="{{ old('product_name', $product->product_name ?? '') }}"
                        required>
                </div>

                {{-- Product Category --}}
                <div class="mb-3">
                    <label for="product_category_id" class="form-label fw-bold">Category</label>
                    <select class="form-select" id="product_category_id" name="product_category_id" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ isset($product) && $product->product_category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label fw-bold">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
                </div>

                {{-- Prices --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="actual_price" class="form-label fw-bold">Actual Price (₹)</label>
                        <input type="number" class="form-control" id="actual_price" name="actual_price"
                            value="{{ old('actual_price', $product->actual_price ?? '') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="discounted_price" class="form-label fw-bold">Discounted Price (₹)</label>
                        <input type="number" class="form-control" id="discounted_price" name="discounted_price"
                            value="{{ old('discounted_price', $product->discounted_price ?? '') }}">
                    </div>
                </div>

                {{-- Stock & SKU --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label fw-bold">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock"
                            value="{{ old('stock', $product->stock ?? '') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="sku" class="form-label fw-bold">SKU</label>
                        <input type="text" class="form-control" id="sku" name="sku"
                            value="{{ old('sku', $product->sku ?? '') }}">
                    </div>
                </div>

                {{-- Available Product Sizes --}}

                <div class="mb-3">
                    <label class="form-label fw-bold">Available Sizes</label>
                    <div id="size-buttons">
                        @php
                            $availableSizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

                            $sizesString = old('sizes', isset($product) ? $product->product_size ?? '' : '');
                            $selectedSizes = $sizesString ? array_map('trim', explode(',', $sizesString)) : [];
                        @endphp

                        @foreach ($availableSizes as $size)
                            <button type="button"
                                class="btn btn-outline-primary me-1 mb-1 size-btn {{ in_array($size, $selectedSizes) ? 'active btn-primary' : '' }}"
                                data-size="{{ $size }}">
                                {{ $size }}
                            </button>
                        @endforeach

                    </div>
                    <input type="hidden" name="sizes" id="sizes"
                        value="{{ old('sizes', isset($product) ? $product->sizes : '') }}">
                </div>

                {{-- Featured Switch --}}
                <div class="form-check form-switch mb-3">
                    <input type="hidden" name="is_featured" value="0">
                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                        {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="is_featured">Featured Product</label>
                </div>

                {{-- Tags --}}
                <div class="mb-3">
                    <label for="tags" class="form-label fw-bold">Tags <small>(comma-separated)</small></label>
                    <textarea class="form-control" id="tags" name="tags">{{ old('tags', isset($product) ? implode(',', $product->tags->pluck('name')->toArray()) : '') }}</textarea>
                </div>

                {{-- Product Image --}}
                <div class="mb-3">
                    <label for="image" class="form-label fw-bold">Product Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    @if (isset($product) && $product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="mt-2 img-thumbnail"
                            style="max-width: 120px;">
                    @endif
                </div>

                {{-- Additional Images --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Additional Images</label>
                    <div class="row">
                        @for ($i = 1; $i <= 6; $i++)
                            <div class="col-md-4 col-6 mb-3">
                                <input type="file" class="form-control" id="ai_{{ $i }}"
                                    name="ai_{{ $i }}" accept="image/*">
                                @if (isset($product) && $product->{'ai_' . $i})
                                    <img src="{{ asset('storage/' . $product->{'ai_' . $i}) }}"
                                        class="mt-2 img-thumbnail" style="max-width: 120px;">
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- Product Info --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">Product Info</label>
                    <div id="product-info-container">
                        @if (isset($productinfo) && $productinfo->count() > 0)
                            @foreach ($productinfo as $info)
                                <div class="row product-info-row mb-2">
                                    <div class="col-md-5 mb-2">
                                        <input type="text" class="form-control" name="property[]"
                                            value="{{ $info->property }}" placeholder="Property">
                                    </div>
                                    <div class="col-md-5 mb-2">
                                        <input type="text" class="form-control" name="value[]"
                                            value="{{ $info->value }}" placeholder="Value">
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <button type="button" class="btn btn-danger remove-info-row">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row product-info-row mb-2">
                                <div class="col-md-5 mb-2">
                                    <input type="text" class="form-control" name="property[]" placeholder="Property">
                                </div>
                                <div class="col-md-5 mb-2">
                                    <input type="text" class="form-control" name="value[]" placeholder="Value">
                                </div>
                                <div class="col-md-2 text-center">
                                    <button type="button" class="btn btn-danger remove-info-row">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-sm btn-primary mt-2" id="add-info-row">
                        <i class="fas fa-plus"></i> Add More
                    </button>
                </div>

                {{-- Submit --}}
                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> {{ isset($product) ? 'Update Product' : 'Add Product' }}
                    </button>
                    <a href="{{ route('backend.products.view') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#is_featured').change(function() {
                $(this).val($(this).is(':checked') ? 1 : 0);
            });

            $('#add-info-row').click(function() {
                let newRow = `
                    <div class="row product-info-row mb-2">
                        <div class="col-md-5 mb-2">
                            <input type="text" class="form-control" name="property[]" placeholder="Property">
                        </div>
                        <div class="col-md-5 mb-2">
                            <input type="text" class="form-control" name="value[]" placeholder="Value">
                        </div>
                        <div class="col-md-2 text-center">
                            <button type="button" class="btn btn-danger remove-info-row">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>`;
                $('#product-info-container').append(newRow);
            });

            $(document).on('click', '.remove-info-row', function() {
                $(this).closest('.product-info-row').remove();
            });

            $('.size-btn').click(function() {
                $(this).toggleClass('active btn-primary btn-outline-primary');

                // Collect selected sizes
                let selected = [];
                $('.size-btn.active').each(function() {
                    selected.push($(this).data('size'));
                });

                // Store as "S, M, L"
                $('#sizes').val(selected.join(', '));
            });
        });
    </script>
@endsection
