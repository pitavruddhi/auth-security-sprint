@extends('layouts.app')

@section('title', 'Browse Products')

@section('content')
<div class="container-fluid py-3">
    <div class="mb-4">
        <h2>Browse Products</h2>
        <p class="text-muted">Discover products available in our store.</p>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('catalog.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Product name</label>
                        <input type="text" name="search" id="search" class="form-control"
                            value="{{ $filters['search'] ?? '' }}" placeholder="Search products...">
                    </div>

                    <div class="col-md-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">All categories</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '' )==$category->
                                id)
                                >
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="min_price" class="form-label">Min price (₹)</label>
                        <input type="number" name="min_price" id="min_price" class="form-control" min="0" step="0.01"
                            value="{{ $filters['min_price'] ?? '' }}">
                    </div>

                    <div class="col-md-2">
                        <label for="max_price" class="form-label">Max price (₹)</label>
                        <input type="number" name="max_price" id="max_price" class="form-control" min="0" step="0.01"
                            value="{{ $filters['max_price'] ?? '' }}">
                    </div>

                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            Go
                        </button>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-outline-secondary">
                        Clear filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($products as $product)
        <div class="col-sm-6 col-lg-4 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <span class="badge bg-light text-dark align-self-start mb-2">
                        {{ $product->category->name ?? 'Uncategorized' }}
                    </span>

                    <h5 class="card-title">{{ $product->title }}</h5>

                    <p class="text-muted small">
                        SKU: {{ $product->sku }}
                    </p>

                    <h5 class="text-primary mb-3">
                        ₹{{ number_format((float) $product->base_price, 2) }}
                    </h5>

                    @if ($product->stock_quantity > 0)
                    <p class="small text-success">In stock</p>
                    @else
                    <p class="small text-danger">Out of stock</p>
                    @endif

                    <a href="{{ route('catalog.show', $product->slug) }}" class="btn btn-outline-primary mt-auto">
                        View Details
                    </a>

                    <form action="{{ route('cart.items.store') }}" method="POST" class="mt-2">
                        @csrf

                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <input type="hidden" name="quantity" value="1">

                        <button type="submit" class="btn btn-primary">
                            Add to Cart
                        </button>
                    </form>

                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                No products match your filters.
            </div>
        </div>
        @endforelse
    </div>

    @if ($products->hasPages())
    <div class="mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection