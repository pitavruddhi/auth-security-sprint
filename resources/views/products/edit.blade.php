
@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="mb-1">Edit Product</h2>
            <p class="text-muted mb-0">
                Update product information, pricing and inventory.
            </p>
        </div>

        <a href="{{ route('admin.products.index') }}"
           class="btn btn-outline-secondary">
            Back to Products
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Product Details</h5>
        </div>

        <div class="card-body">
            <form method="POST"
                  action="{{ route('admin.products.update', $product) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">
                            Product Title *
                        </label>

                        <input
                            type="text"
                            id="title"
                            name="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $product->title) }}"
                            maxlength="255"
                            required
                        >

                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="category_id" class="form-label">
                            Category *
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                            class="form-select @error('category_id') is-invalid @enderror"
                            required
                        >
                            <option value="">Select category</option>

                            @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @selected(
                                        old('category_id', $product->category_id) == $category->id
                                    )
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="slug" class="form-label">Slug</label>

                        <input
                            type="text"
                            id="slug"
                            name="slug"
                            class="form-control @error('slug') is-invalid @enderror"
                            value="{{ old('slug', $product->slug) }}"
                            placeholder="Leave blank to generate from title"
                        >

                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="sku" class="form-label">SKU *</label>

                        <input
                            type="text"
                            id="sku"
                            name="sku"
                            class="form-control @error('sku') is-invalid @enderror"
                            value="{{ old('sku', $product->sku) }}"
                            maxlength="100"
                            required
                        >

                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="base_price" class="form-label">
                            Base Price (₹) *
                        </label>

                        <input
                            type="number"
                            id="base_price"
                            name="base_price"
                            class="form-control @error('base_price') is-invalid @enderror"
                            value="{{ old('base_price', $product->base_price) }}"
                            min="0"
                            step="0.01"
                            required
                        >

                        @error('base_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="stock_quantity" class="form-label">
                            Stock Quantity *
                        </label>

                        <input
                            type="number"
                            id="stock_quantity"
                            name="stock_quantity"
                            class="form-control @error('stock_quantity') is-invalid @enderror"
                            value="{{ old('stock_quantity', $product->stock_quantity) }}"
                            min="0"
                            step="1"
                            required
                        >

                        @error('stock_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                        >{{ old('description', $product->description) }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Update Product
                    </button>

                    <a href="{{ route('admin.products.index') }}"
                       class="btn btn-light border">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
