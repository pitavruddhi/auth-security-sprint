
@extends('layouts.app')

@section('title', 'Deleted Products')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="mb-1">Deleted Products</h2>
            <p class="text-muted mb-0">
                Restore deleted products or permanently remove them.
            </p>
        </div>

        <a href="{{ route('admin.products.index') }}"
           class="btn btn-outline-secondary">
            Back to Products
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Deleted At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $products->firstItem() + $loop->index }}</td>
                                <td>{{ $product->title }}</td>
                                <td><code>{{ $product->sku }}</code></td>
                                <td>{{ $product->category->name ?? 'Unassigned' }}</td>
                                <td>{{ $product->deleted_at?->format('d M Y, h:i A') }}</td>

                                <td class="text-end text-nowrap">
                                    <form
                                        action="{{ route('admin.products.restore', $product->id) }}"
                                        method="POST"
                                        class="d-inline"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            Restore
                                        </button>
                                    </form>

                                    <form
                                        action="{{ route('admin.products.forceDelete', $product->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Permanently delete this product? This cannot be undone.')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Delete Permanently
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <h5 class="text-muted">Trash is empty</h5>
                                    <p class="text-muted mb-0">
                                        Deleted products will appear here.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
                {{ $products->links('pagination::bootstrap-5') }}
            @endif
        </div>
    </div>
</div>
@endsection
