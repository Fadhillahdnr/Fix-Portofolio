@extends('layouts.app')

@php
    $title = 'Profile Archive — '.config('portfolio.owner_name');
    $description = $profile?->short_bio;
@endphp

@section('content')
    <header class="page-command container">
        <p class="system-label">Directory / 02 / Profile archive</p>
        <div class="page-command__grid">
            <h1>Engineer profile,<br><em>not a generic bio.</em></h1>
            <div>
                <p>{{ $profile?->full_bio ?: $profile?->short_bio }}</p>
                <dl class="inline-specs">
                    <div><dt>Location</dt><dd>{{ $profile?->location ?: 'Indonesia' }}</dd></div>
                    <div><dt>Direction</dt><dd>Fullstack Java / Backend</dd></div>
                    <div><dt>Status</dt><dd>{{ $profile?->availability ?: 'Open to opportunities' }}</dd></div>
                </dl>
            </div>
        </div>
    </header>

    <section class="runtime-section philosophy-section">
        <div class="container split-console">
            <div>
                <p class="system-label">01 / Engineering philosophy</p>
                <h2>Understand the system before optimizing the layer.</h2>
            </div>
            <div class="prose-console">
                <p>
                    I approach software as a connected system: interface decisions affect APIs,
                    data models affect operations, and deployment constraints affect architecture.
                </p>
                <p>
                    My technical path moves across web applications, enterprise Java, database
                    engineering, IoT devices, embedded audio, predictive analytics, and NLP.
                </p>
            </div>
        </div>
    </section>

    <section class="runtime-section sectors-section">
        <div class="container">
            <header class="section-heading reveal">
                <div>
                    <p class="system-label">02 / Capability sectors</p>
                    <h2>Technology map</h2>
                </div>
                <span>{{ $skills->count() }} verified nodes</span>
            </header>
            <div class="sector-grid">
                @foreach ($skills->groupBy('category') as $category => $items)
                    <article class="sector-card reveal">
                        <header>
                            <span>SECTOR_{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3>{{ $category ?: 'Engineering' }}</h3>
                        </header>
                        <ol>
                            @foreach ($items as $skill)
                                <li>
                                    <span>0x{{ strtoupper(dechex($loop->parent->index * 8 + $loop->index)) }}</span>
                                    <strong>{{ $skill->name }}</strong>
                                </li>
                            @endforeach
                        </ol>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="runtime-section timeline-section">
        <div class="container split-console">
            <header>
                <p class="system-label">03 / Technical timeline</p>
                <h2>Learning through building.</h2>
            </header>
            <div class="technical-timeline">
                @foreach ($experiences as $item)
                    <article class="reveal">
                        <time>
                            {{ $item->start_date->format('Y') }} —
                            {{ $item->is_current ? 'NOW' : $item->end_date?->format('Y') }}
                        </time>
                        <div>
                            <span>{{ $item->company }}</span>
                            <h3>{{ $item->position }}</h3>
                            <p>{{ $item->description }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
