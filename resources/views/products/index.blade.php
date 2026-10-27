
@extends('layouts.app')

@section('title', 'Manage Products')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="mb-1">Product Management</h2>
            <p class="text-muted mb-0">Manage products, prices, categories and inventory.</p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            + Add Product
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 mb-4">
                <div class="col-md-9">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search by product title or SKU..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark">Search</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $products->firstItem() + $loop->index }}</td>

                                <td>
                                    <span class="fw-semibold">{{ $product->title }}</span>
                                    <br>
                                    <small class="text-muted">{{ $product->slug }}</small>
                                </td>

                                <td><code>{{ $product->sku }}</code></td>

                                <td>{{ $product->category->name ?? 'Unassigned' }}</td>

                                <td>₹{{ number_format((float) $product->base_price, 2) }}</td>

                                <td>
                                    @if ($product->stock_quantity === 0)
                                        <span class="badge bg-danger">Out of stock</span>
                                    @elseif ($product->stock_quantity <= 5)
                                        <span class="badge bg-warning text-dark">
                                            {{ $product->stock_quantity }} left
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            {{ $product->stock_quantity }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-end text-nowrap">
                                    <a
                                        href="{{ route('admin.products.edit', $product) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.products.destroy', $product) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Move this product to trash?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <h5 class="text-muted">No products found</h5>
                                    <p class="text-muted mb-3">
                                        Create a product or try a different search.
                                    </p>

                                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                        Add First Product
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3">
                    <small class="text-muted">
                        Showing {{ $products->firstItem() }} to {{ $products->lastItem() }}
                        of {{ $products->total() }} products
                    </small>

                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
