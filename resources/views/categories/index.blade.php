
@extends('layouts.app')

@section('title', 'Manage Categories')

@section('content')

<div class="container-fluid py-3">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="mb-1">Category Management</h2>
            <p class="text-muted mb-0">
                Manage your product categories and hierarchy.
            </p>
        </div>

        <a href="{{ route('admin.categories.create') }}"
           class="btn btn-primary">
            + Add Category
        </a>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show"
             role="alert">
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Categories Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">
            <h5 class="mb-0">All Categories</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Category Name</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Parent Category</th>
                            <th scope="col">Child Categories</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                {{-- Serial Number --}}
                                <td>
                                    {{ $categories->firstItem() + $loop->index }}
                                </td>

                                {{-- Category Name --}}
                                <td>
                                    <span class="fw-semibold">
                                        {{ $category->name }}
                                    </span>
                                </td>

                                {{-- Slug --}}
                                <td>
                                    <code>{{ $category->slug }}</code>
                                </td>

                                {{-- Parent Category --}}
                                <td>
                                    @if ($category->parent)
                                        <span class="badge bg-secondary">
                                            {{ $category->parent->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">
                                            Top-level category
                                        </span>
                                    @endif
                                </td>

                                {{-- Child Count --}}
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ $category->children_count }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="text-end text-nowrap">

                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.categories.destroy', $category) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this category?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger">
                                            Delete
                                        </button>
                                    </form>

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <h5 class="text-muted">
                                        No categories found
                                    </h5>

                                    <p class="text-muted mb-3">
                                        Start by creating your first category.
                                    </p>

                                    <a href="{{ route('admin.categories.create') }}"
                                       class="btn btn-primary">
                                        Create Category
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            @if ($categories->hasPages())
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">

                    <small class="text-muted">
                        Showing {{ $categories->firstItem() ?? 0 }}
                        to {{ $categories->lastItem() ?? 0 }}
                        of {{ $categories->total() }} categories
                    </small>

                    {{ $categories->links('pagination::bootstrap-5') }}

                </div>
            @endif

        </div>
    </div>

</div>

@endsection
