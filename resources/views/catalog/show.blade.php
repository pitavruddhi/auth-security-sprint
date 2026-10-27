
@extends('layouts.app')

@section('title', $product->title)

@section('content')
<div class="container py-4">
    <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary mb-4">
        &larr; Back to Catalog
    </a>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">
            <span class="badge bg-secondary mb-3">
                {{ $product->category->name ?? 'Uncategorized' }}
            </span>

            <h1 class="mb-3">{{ $product->title }}</h1>

            <p class="text-muted">SKU: {{ $product->sku }}</p>

            <h2 class="text-primary mb-4">
                ₹{{ number_format((float) $product->base_price, 2) }}
            </h2>

            <h5>Description</h5>
            <p>{{ $product->description ?: 'No description available.' }}</p>

            <hr>

            @if ($product->stock_quantity > 0)
                <p class="text-success">
                    In stock — {{ $product->stock_quantity }} available
                </p>
            @else
                <p class="text-danger">Currently out of stock</p>
            @endif
        </div>
    </div>
</div>
@endsection
