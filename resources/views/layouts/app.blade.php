<!doctype html>
<html lang="id" data-theme="engineering" data-render-profile="balanced">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#070a0d">
        <title>{{ $title ?? config('portfolio.owner_name').' — Software Engineer' }}</title>
        <meta
            name="description"
            content="{{
                $description
                    ?? 'Engineering portfolio Muhamad Fadhillah Dinurahman: fullstack, backend, '
                    .'database, IoT, embedded systems, dan AI/ML.'
            }}"
        >
        <link rel="canonical" href="{{ url()->current() }}">
        <meta
            property="og:title"
            content="{{ $title ?? config('portfolio.owner_name').' — Software Engineer' }}"
        >
        <meta
            property="og:description"
            content="{{ $description ?? 'Building systems from interface to hardware.' }}"
        >
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta name="twitter:card" content="summary_large_image">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body data-route="{{ request()->route()?->getName() }}">
        @php
            $navigation = [
                ['route' => 'home', 'index' => '01', 'label' => 'Home'],
                ['route' => 'about', 'index' => '02', 'label' => 'About'],
                ['route' => 'projects.index', 'index' => '03', 'label' => 'Projects'],
                ['route' => 'lab', 'index' => '04', 'label' => 'Lab'],
                ['route' => 'contact', 'index' => '05', 'label' => 'Contact'],
            ];
            $currentRoute = collect($navigation)->first(
                fn (array $item) => request()->routeIs($item['route']),
            );
        @endphp

        <a class="skip-link" href="#content">Lewati ke konten</a>
        <div class="page-progress" data-page-progress aria-hidden="true"></div>
        <div class="interface-grid" aria-hidden="true"></div>

        <header class="runtime-header">
            <a
                class="runtime-brand"
                href="{{ route('home') }}"
                aria-label="Beranda Fadhillah"
            >
                <strong>FADHILLAH</strong>
                <span>SOFTWARE ENGINEER / PORTFOLIO {{ date('Y') }}</span>
            </a>

            <div class="runtime-route" aria-live="polite">
                <span>ROUTE</span>
                <strong>{{ $currentRoute['label'] ?? 'Case Study' }}</strong>
            </div>

            <div class="runtime-actions">
                <span class="node-status">
                    <i aria-hidden="true"></i>
                    NODE ONLINE
                </span>
                <button
                    class="header-action"
                    type="button"
                    data-config-open
                    aria-controls="interface-config"
                    aria-expanded="false"
                >
                    <svg aria-hidden="true" viewBox="0 0 24 24">
                        <path d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z" />
                        <path
                            d="M4.9 19.1 3.5 16.7l1.7-2.1a8 8 0 0 1 0-5.2L3.5 7.3
                                l1.4-2.4 2.7.4A8 8 0 0 1 12 2.7l1-2.5h2.8l1 2.5
                                a8 8 0 0 1 4.4 2.6l2.7-.4 1.4 2.4-1.7 2.1a8 8 0 0 1 0 5.2
                                l1.7 2.1-1.4 2.4-2.7-.4a8 8 0 0 1-4.4 2.6l-1 2.5H13
                                l-1-2.5a8 8 0 0 1-4.4-2.6l-2.7.4Z"
                        />
                    </svg>
                    Config
                </button>
                <button
                    class="header-action menu-button"
                    type="button"
                    aria-expanded="false"
                    aria-controls="primary-navigation"
                >
                    <svg aria-hidden="true" viewBox="0 0 24 24">
                        <path d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                    Menu
                </button>
            </div>
        </header>

        <nav
            id="primary-navigation"
            class="runtime-navigation"
            aria-label="Navigasi utama"
        >
            <p class="system-label">Directory index</p>
            <div class="runtime-navigation__links">
                @foreach ($navigation as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        @class(['is-active' => request()->routeIs($item['route'])])
                    >
                        <span>{{ $item['index'] }}</span>
                        <strong>{{ $item['label'] }}</strong>
                        <svg aria-hidden="true" viewBox="0 0 24 24">
                            <path d="M5 12h14M14 7l5 5-5 5" />
                        </svg>
                    </a>
                @endforeach
            </div>
            <div class="runtime-navigation__meta">
                <span>ASIA / JAKARTA</span>
                <time data-local-time>--:--:--</time>
            </div>
        </nav>

        <x-config-panel />

        <main id="content">
            @if (session('success'))
                <div class="container alert success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="runtime-footer">
            <div class="container runtime-footer__grid">
                <div>
                    <p class="system-label">Engineering node</p>
                    <a class="runtime-footer__cta" href="{{ route('contact') }}">
                        Start a conversation
                        <svg aria-hidden="true" viewBox="0 0 24 24">
                            <path d="M5 12h14M14 7l5 5-5 5" />
                        </svg>
                    </a>
                </div>
                <dl class="runtime-footer__telemetry">
                    <div><dt>Build</dt><dd>Portfolio / V2</dd></div>
                    <div><dt>Status</dt><dd>Node online</dd></div>
                    <div><dt>Timezone</dt><dd>Asia/Jakarta</dd></div>
                    <div><dt>Local time</dt><dd><time data-local-time>--:--:--</time></dd></div>
                </dl>
            </div>
            <div class="container runtime-footer__bottom">
                <span>© {{ date('Y') }} {{ config('portfolio.owner_name') }}</span>
                <div>
                    <a href="{{ route('services') }}">Capabilities</a>
                    <a href="{{ route('privacy') }}">Privacy</a>
                </div>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>
