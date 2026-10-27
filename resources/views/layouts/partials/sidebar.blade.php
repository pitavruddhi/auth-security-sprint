@auth
@if (auth()->user()->role === 'admin')
<aside class="app-sidebar p-3">
    <h6 class="text-uppercase text-secondary small mb-3">
        Admin Menu
    </h6>

    <nav class="nav flex-column">
        <a href="{{ route('admin.dashboard') }}"
            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <a href="{{ route('admin.categories.index') }}"
            class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            Categories
        </a>

        <a href="{{ url('/admin/products') }}" class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}">
            Products
        </a>
        <a href="{{ route('admin.products.trash') }}"
            class="nav-link {{ request()->routeIs('admin.products.trash') ? 'active' : '' }}">
            Trash
        </a>
    </nav>
</aside>

@elseif (auth()->user()->role === 'customer')
<aside class="app-sidebar p-3">
    <h6 class="text-uppercase text-secondary small mb-3">
        Customer Menu
    </h6>

    <nav class="nav flex-column">
        <a href="{{ route('customer.dashboard') }}"
            class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            Dashboard
        </a>

        <a href="{{ route('catalog.index') }}" class="nav-link {{ request()->routeIs('catalog.*') ? 'active' : '' }}">
            Browse Products
        </a>
    </nav>
</aside>
@endif
@endauth