@extends('backend.layouts.master')

@section('title')
    {{ isset($product) ? 'Edit Product' : 'Add a Product' }}
@endsection

@section('content')
    <!-- DEVELOPED BY AAROOSHI.COM -->
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">{{ isset($product) ? 'Edit Product' : 'Add a Product' }}</h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form
                            action="{{ isset($product) ? route('backend.products.store', $product->id) : route('backend.products.store', ['product_id' => null]) }}"
                            method="POST" enctype="multipart/form-data"> @csrf

                            {{-- Product Name --}}
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name"
                                    placeholder="Enter product name"
                                    value="{{ old('product_name', $product->product_name ?? '') }}" required>
                            </div>

                            {{-- Product Category --}}
                            <div class="mb-3">
                                <label for="product_category_id" class="form-label">Category</label>
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
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
                            </div>

                            {{-- Prices --}}
                            <div class="row">
                                <div class="col-sm-6 col-12 mb-3">
                                    <label for="actual_price" class="form-label">Actual Price</label>
                                    <input type="number" class="form-control" id="actual_price" name="actual_price"
                                        value="{{ old('actual_price', $product->actual_price ?? '') }}" required>
                                </div>
                                <div class="col-sm-6 col-12 mb-3">
                                    <label for="discounted_price" class="form-label">Discounted Price</label>
                                    <input type="number" class="form-control" id="discounted_price" name="discounted_price"
                                        value="{{ old('discounted_price', $product->discounted_price ?? '') }}">
                                </div>
                            </div>

                            {{-- Stock & SKU --}}
                            <div class="row">
                                <div class="col-sm-6 col-12 mb-3">
                                    <label for="stock" class="form-label">Stock</label>
                                    <input type="number" class="form-control" id="stock" name="stock"
                                        value="{{ old('stock', $product->stock ?? '') }}" required>
                                </div>
                                <div class="col-sm-6 col-12 mb-3">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control" id="sku" name="sku"
                                        value="{{ old('sku', $product->sku ?? '') }}">
                                </div>
                            </div>

                            {{-- Is Featured Switch --}}
                            <div class="mb-3 form-check form-switch">
                                <input type="hidden" name="is_featured" value="0"> {{-- Hidden input to send 0 when unchecked --}}
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                    value="1"
                                    {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Featured Product</label>
                            </div>

                            {{-- Tags --}}
                            <div class="mb-3">
                                <label for="tags" class="form-label">Tags (comma-separated)</label>
                                <textarea class="form-control" id="tags" name="tags">{{ old('tags', isset($product) ? implode(',', $product->tags->pluck('name')->toArray()) : '') }}</textarea>
                            </div>

                            {{-- Image Upload --}}
                            <div class="mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                @if (isset($product) && $product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="mt-2"
                                        width="100">
                                @endif
                            </div>

                            {{-- Add other product images --}}
                            <div class="mb-3">
                                <label class="form-label uppercase-label">Additional Images</label>
                                <div class="row">
                                    @for ($i = 1; $i <= 6; $i++)
                                        <div class="col-4 p-2">
                                            <label for="ai_{{ $i }}" class="form-label">Additional Image
                                                {{ $i }}</label>
                                            <input type="file" class="form-control" id="ai_{{ $i }}"
                                                name="ai_{{ $i }}" accept="image/*">
                                            @if (isset($product) && $product->{'ai_' . $i})
                                                <img src="{{ asset('storage/' . $product->{'ai_' . $i}) }}"
                                                    alt="Product Image" class="mt-2" width="100">
                                            @endif
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            {{-- Product Info Section --}}
                            <div class="mb-3">
                                <label class="form-label uppercase-label">Product Info</label>
                                <div id="product-info-container">
                                    @if (isset($productinfo) && $productinfo->count() > 0)
                                        @foreach ($productinfo as $info)
                                            <div class="row product-info-row mb-2">
                                                <div class="col-5">
                                                    <input type="text" class="form-control" name="property[]"
                                                        value="{{ $info->property }}" placeholder="Property">
                                                </div>
                                                <div class="col-5">
                                                    <input type="text" class="form-control" name="value[]"
                                                        value="{{ $info->value }}" placeholder="Value">
                                                </div>
                                                <div class="col-2 text-center">
                                                    <button type="button" class="btn btn-danger remove-info-row">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row product-info-row mb-2">
                                            <div class="col-5">
                                                <input type="text" class="form-control" name="property[]"
                                                    placeholder="Property">
                                            </div>
                                            <div class="col-5">
                                                <input type="text" class="form-control" name="value[]"
                                                    placeholder="Value">
                                            </div>
                                            <div class="col-2 text-center">
                                                <button type="button" class="btn btn-danger remove-info-row">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-primary mt-2" id="add-info-row">
                                    <i class="fas fa-plus"></i> Add More
                                </button>
                            </div>

                            {{-- Submit Button --}}
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ isset($product) ? 'Update Product' : 'Add Product' }}
                                </button>
                                <a href="{{ route('backend.products.view') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            //featured toggle
            $('#is_featured').change(function() {
                if ($(this).is(':checked')) {
                    $(this).val(1); // Set value to 1 if checked
                } else {
                    $(this).val(0); // Set value to 0 if unchecked
                }
            });

            // Add more rows (in product-info section)
            $('#add-info-row').click(function() {
                let newRow = `
                    <div class="row product-info-row mb-2">
                        <div class="col-5">
                            <input type="text" class="form-control" name="property[]" placeholder="Property">
                        </div>
                        <div class="col-5">
                            <input type="text" class="form-control" name="value[]" placeholder="Value">
                        </div>
                        <div class="col-2 text-center">
                            <button type="button" class="btn btn-danger remove-info-row">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>`;
                $('#product-info-container').append(newRow);
            });

            // Remove a row
            $(document).on('click', '.remove-info-row', function() {
                $(this).closest('.product-info-row').remove();
            });

        });
    </script>
@endsection
