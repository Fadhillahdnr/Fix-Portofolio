<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <title>{{ $title ?? 'Studio Admin' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-body">
        <div class="admin-shell">
            <aside>
                <a class="brand" href="{{ route('admin.dashboard') }}">
                    <span>F.</span>Studio
                </a>
                <nav aria-label="Navigasi admin">
                    <a href="{{ route('admin.dashboard') }}">Ringkasan</a>
                    <a href="{{ route('admin.projects.index') }}">Proyek</a>
                    <a href="{{ route('admin.content') }}">Konten & pesan</a>
                    <a
                        href="{{ route('home') }}"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        Lihat situs ↗
                    </a>
                </nav>
                <form method="post" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="btn ghost" type="submit">Keluar</button>
                </form>
            </aside>

            <main>
                <header class="admin-top">
                    <div>
                        <p class="eyebrow">Content studio</p>
                        <h1>
                            @yield('heading', 'Dashboard')
                        </h1>
                    </div>
                    <span>{{ auth()->user()->name }}</span>
                </header>

                @if (session('success'))
                    <div class="alert success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert error" role="alert">
                        Periksa kembali input yang ditandai.
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </body>
</html>
