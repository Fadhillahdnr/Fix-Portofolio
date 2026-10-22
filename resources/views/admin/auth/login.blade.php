<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <title>Studio Access</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="login-body">
        <main class="login-card">
            <a class="brand" href="{{ route('home') }}"><span>F.</span>Studio</a>
            <p class="eyebrow">Private access</p>
            <h1>Masuk ke studio.</h1>
            <p>Kelola karya dan konten portofolio dari satu tempat.</p>

            <form method="post" action="{{ route('admin.login.store') }}">
                @csrf
                <label>
                    Email
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                    >
                    @error('email')
                        <small>{{ $message }}</small>
                    @enderror
                </label>
                <label>
                    Kata sandi
                    <input
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                    >
                </label>
                <label class="check">
                    <input type="checkbox" name="remember" value="1">
                    Ingat perangkat ini
                </label>
                <button class="btn primary" type="submit">Masuk aman ↗</button>
            </form>
        </main>
    </body>
</html>
