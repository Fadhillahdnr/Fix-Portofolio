@extends('layouts.app')

@php
    $title = 'Contact Node — '.config('portfolio.owner_name');
    $description = 'Hubungi Muhamad Fadhillah Dinurahman untuk peluang kerja atau kolaborasi.';
@endphp

@section('content')
    <header class="page-command container contact-command">
        <p class="system-label">Directory / 05 / Contact node</p>
        <div class="page-command__grid">
            <h1>Open a channel.<br><em>Start with context.</em></h1>
            <dl class="contact-status">
                <div><dt>Status</dt><dd>{{ $profile?->availability ?: 'Available' }}</dd></div>
                <div><dt>Location</dt><dd>{{ $profile?->location ?: 'Indonesia' }}</dd></div>
                <div><dt>Timezone</dt><dd>Asia / Jakarta</dd></div>
                <div><dt>Local time</dt><dd><time data-local-time>--:--:--</time></dd></div>
            </dl>
        </div>
    </header>

    <section class="runtime-section contact-section">
        <div class="container contact-console">
            <aside>
                <p class="system-label">Available channels</p>
                <div class="channel-list">
                    @foreach ($socials as $social)
                        <a
                            href="{{ $social->url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <span>0{{ $loop->iteration }}</span>
                            <strong>{{ $social->label }}</strong>
                            <small>{{ strtoupper($social->platform) }}</small>
                            <svg aria-hidden="true" viewBox="0 0 24 24">
                                <path d="M7 17 17 7M8 7h9v9" />
                            </svg>
                        </a>
                    @endforeach
                </div>
            </aside>

            <form
                class="terminal-form"
                method="post"
                action="{{ route('contact.store') }}"
            >
                @csrf
                <div class="terminal-form__header">
                    <span>MESSAGE_COMPOSER</span>
                    <i>SECURE / CSRF</i>
                </div>
                <div class="form-grid">
                    <label for="name">01 / Name</label>
                    <div>
                        <input
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autocomplete="name"
                        >
                        @error('name')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>
                    <label for="email">02 / Email</label>
                    <div>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                        >
                        @error('email')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>
                    <label for="subject">03 / Subject</label>
                    <div>
                        <input
                            id="subject"
                            name="subject"
                            value="{{ old('subject') }}"
                            required
                        >
                        @error('subject')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>
                    <label for="message">04 / Context</label>
                    <div>
                        <textarea
                            id="message"
                            name="message"
                            rows="7"
                            required
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <label class="honeypot" aria-hidden="true">
                    Website
                    <input name="website" tabindex="-1" autocomplete="off">
                </label>
                <button class="button button--primary" type="submit">
                    Transmit message
                    <svg aria-hidden="true" viewBox="0 0 24 24">
                        <path d="m4 4 17 8-17 8 4-8-4-8Zm4 8h13" />
                    </svg>
                </button>
            </form>
        </div>
    </section>
@endsection
