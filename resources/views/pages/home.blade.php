@extends('layouts.app')

@php
    $title = config('portfolio.owner_name').' — Fullstack Software Engineer';
@endphp

@section('content')
    <section class="command-hero">
        <div class="container command-hero__grid">
            <div class="command-hero__copy">
                <div class="command-hero__status">
                    <span class="node-status"><i aria-hidden="true"></i> AVAILABLE</span>
                    <span>FULLSTACK / BACKEND / IOT / AI</span>
                </div>
                <p class="system-label">Engineering portfolio // runtime 02</p>
                <h1>
                    Building systems<br>
                    <em>from interface</em><br>
                    to hardware.
                </h1>
                <p class="command-hero__lead">
                    {{
                        $profile?->short_bio
                            ?? 'Fullstack software engineer focused on Java, Spring Boot, '
                            .'PostgreSQL, modern frontend systems, IoT, and applied AI.'
                    }}
                </p>
                <div class="command-hero__actions">
                    <a class="button button--primary" href="{{ route('projects.index') }}">
                        Open project archive
                        <svg aria-hidden="true" viewBox="0 0 24 24">
                            <path d="M5 12h14M14 7l5 5-5 5" />
                        </svg>
                    </a>
                    <a class="button button--ghost" href="{{ route('about') }}">
                        Inspect profile
                    </a>
                </div>
            </div>

            <div class="command-hero__visual" data-three-scene>
                <div class="runtime-orbit" aria-hidden="true">
                    <span>UI</span><span>API</span><span>DB</span><span>IOT</span>
                    <div class="orb-fallback"></div>
                </div>
                <dl class="hero-telemetry">
                    <div><dt>Primary stack</dt><dd>Java / Spring / PostgreSQL</dd></div>
                    <div><dt>Interface</dt><dd>TypeScript / Nuxt / Laravel</dd></div>
                    <div><dt>Specialization</dt><dd>Web / IoT / Applied AI</dd></div>
                    <div><dt>Render</dt><dd data-runtime-render>Balanced</dd></div>
                </dl>
            </div>
        </div>
        <div class="container active-stack">
            <span>&gt; ACTIVE_STACK</span>
            <div>JAVA / SPRING_BOOT / POSTGRESQL / TYPESCRIPT / LARAVEL / DOCKER</div>
        </div>
    </section>

    <section class="runtime-section metrics-section">
        <div class="container metrics-grid">
            <div class="section-intro">
                <p class="system-label">01 / Engineering metrics</p>
                <h2>Measured from the archive, not invented.</h2>
            </div>
            <div class="metric-console">
                @foreach ($metrics as $metric)
                    <article>
                        <span>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <strong>{{ str_pad($metric['value'], 2, '0', STR_PAD_LEFT) }}</strong>
                        <p>{{ $metric['label'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="runtime-section archive-section">
        <div class="container">
            <header class="section-heading">
                <div>
                    <p class="system-label">02 / Selected cases</p>
                    <h2>Project archive</h2>
                </div>
                <a class="text-link" href="{{ route('projects.index') }}">
                    View all records <span>({{ $projects->count() }})</span>
                </a>
            </header>
            <div class="archive-list">
                @forelse ($projects as $project)
                    <x-project-card :project="$project" />
                @empty
                    <div class="system-empty">
                        <span>NO_FEATURED_RECORDS</span>
                        <p>Projects are being indexed. The complete archive remains available.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="runtime-section capability-section">
        <div class="container capability-layout">
            <header class="section-heading section-heading--vertical">
                <p class="system-label">03 / Capability map</p>
                <h2>Systems thinking across the whole stack.</h2>
                <a class="text-link" href="{{ route('services') }}">Inspect capabilities</a>
            </header>
            <div class="capability-grid">
                @foreach ($services as $service)
                    <article class="capability-card">
                        <span>0{{ $loop->iteration }}</span>
                        <h3>{{ $service->title }}</h3>
                        <p>{{ $service->description }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="runtime-section focus-section">
        <div class="container focus-panel">
            <p class="system-label">04 / Current direction</p>
            <div>
                <h2>Fullstack engineering as the core.<br>IoT and AI as the differentiator.</h2>
                <p>
                    My work connects application interfaces, backend services, databases,
                    deployment, and physical devices into systems that can be understood,
                    tested, and extended.
                </p>
            </div>
            <a class="button button--primary" href="{{ route('contact') }}">
                Connect with me
                <svg aria-hidden="true" viewBox="0 0 24 24">
                    <path d="M5 12h14M14 7l5 5-5 5" />
                </svg>
            </a>
        </div>
    </section>
@endsection
