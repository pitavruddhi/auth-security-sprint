
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'E-Commerce Admin')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
        }

        .app-sidebar {
            width: 250px;
            min-height: calc(100vh - 56px);
            flex-shrink: 0;
            background: #172033;
        }

        .app-sidebar .nav-link {
            color: #cbd5e1;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 5px;
        }

        .app-sidebar .nav-link:hover,
        .app-sidebar .nav-link.active {
            color: #fff;
            background: #334155;
        }

        .app-main {
            min-width: 0;
            flex: 1;
        }

        @media (max-width: 767px) {
            .app-shell {
                flex-direction: column;
            }

            .app-sidebar {
                width: 100%;
                min-height: auto;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-light">

    {{-- Reusable top navbar --}}
    @include('layouts.partials.navbar')

    <div class="d-flex app-shell">

        {{-- Role-specific sidebar --}}
        @include('layouts.partials.sidebar')

        <main class="app-main p-3 p-md-4">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
