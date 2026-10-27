
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            ShopAdmin
        </a>

        <div class="ms-auto d-flex gap-3 align-items-center">
            @auth
                <span class="text-white">
                    Hello, {{ auth()->user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="btn btn-outline-light btn-sm">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('catalog.index')}}"
                   class="btn btn-outline-light btn-sm">
                    Browse Products
                </a>

                <a href="{{ route('login') }}"
                   class="btn btn-outline-light btn-sm">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="btn btn-primary btn-sm">
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>
