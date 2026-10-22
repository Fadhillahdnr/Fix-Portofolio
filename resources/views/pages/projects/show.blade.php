@extends('layouts.app')

@php
    $title = $project->title.' — Engineering Case Study';
    $description = $project->summary;
@endphp

@section('content')
    <article class="case-study" data-case-study>
        <div class="case-progress" aria-hidden="true">
            <span data-case-progress-bar></span>
        </div>

        <header class="case-hero container">
            <a class="case-back" href="{{ route('projects.index') }}">
                <svg aria-hidden="true" viewBox="0 0 24 24">
                    <path d="M19 12H5M10 7l-5 5 5 5" />
                </svg>
                Back to archive
            </a>
            <div class="case-hero__index">
                <span>CASE_{{ str_pad($project->sort_order + 1, 3, '0', STR_PAD_LEFT) }}</span>
                <span>{{ $project->published_at?->format('Y') ?: 'ARCHIVE' }}</span>
            </div>
            <div class="case-hero__grid">
                <div>
                    <p class="system-label">
                        {{ $project->categories->pluck('name')->join(' / ') ?: 'Engineering case' }}
                    </p>
                    <h1>{{ $project->title }}</h1>
                    <p>{{ $project->summary }}</p>
                    @if ($project->demo_url || $project->repository_url)
                        <div class="case-hero__actions">
                            @if ($project->demo_url)
                                <a
                                    class="button button--primary"
                                    href="{{ $project->demo_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    Live demo
                                    <svg aria-hidden="true" viewBox="0 0 24 24">
                                        <path d="M7 17 17 7M8 7h9v9" />
                                    </svg>
                                </a>
                            @endif
                            @if ($project->repository_url)
                                <a
                                    class="button button--ghost"
                                    href="{{ $project->repository_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    Repository
                                    <svg aria-hidden="true" viewBox="0 0 24 24">
                                        <path d="M7 17 17 7M8 7h9v9" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
                <dl class="case-specs">
                    @if ($project->role)
                        <div><dt>Role</dt><dd>{{ $project->role }}</dd></div>
                    @endif
                    @if ($project->published_at)
                        <div><dt>Entry year</dt><dd>{{ $project->published_at->format('Y') }}</dd></div>
                    @endif
                    @if ($project->categories->isNotEmpty())
                        <div><dt>Type</dt><dd>{{ $project->categories->pluck('name')->join(' / ') }}</dd></div>
                    @endif
                    <div><dt>Status</dt><dd>{{ strtoupper($project->status) }}</dd></div>
                </dl>
            </div>
        </header>

        <div class="container case-visual">
            @if ($project->cover_path)
                <img
                    src="{{ $project->cover_path }}"
                    alt="Sampul proyek {{ $project->title }}"
                    width="1600"
                    height="960"
                    fetchpriority="high"
                >
            @else
                <div class="case-visual__fallback" aria-hidden="true">
                    <span>{{ strtoupper(substr($project->slug, 0, 3)) }}</span>
                    <div></div>
                    <strong>{{ $project->title }}</strong>
                </div>
            @endif
        </div>

        <div class="container case-layout">
            <aside class="case-index">
                <p class="system-label">Case index</p>
                <nav aria-label="Daftar isi studi kasus">
                    <a href="#overview" data-case-link="overview"><span>01</span> Overview</a>
                    @if ($project->problem)
                        <a href="#challenge" data-case-link="challenge"><span>02</span> Challenge</a>
                    @endif
                    @if ($project->solution)
                        <a href="#approach" data-case-link="approach"><span>03</span> Approach</a>
                    @endif
                    <a href="#technology" data-case-link="technology"><span>04</span> Technology</a>
                    @if ($project->result)
                        <a href="#result" data-case-link="result"><span>05</span> Result</a>
                    @endif
                    @if ($project->media->isNotEmpty())
                        <a href="#gallery" data-case-link="gallery"><span>06</span> Gallery</a>
                    @endif
                </nav>
                <div class="case-index__progress">
                    <span>CASE_PROGRESS</span>
                    <strong data-case-progress-value>00%</strong>
                </div>
            </aside>

            <div class="case-content">
                <section id="overview" class="case-section" data-case-section>
                    <p class="system-label">01 / Overview</p>
                    <h2>System context.</h2>
                    <p>{{ $project->summary }}</p>
                </section>

                @if ($project->problem)
                    <section id="challenge" class="case-section" data-case-section>
                        <p class="system-label">02 / Challenge</p>
                        <h2>The problem space.</h2>
                        <p>{{ $project->problem }}</p>
                    </section>
                @endif

                @if ($project->solution)
                    <section id="approach" class="case-section" data-case-section>
                        <p class="system-label">03 / Approach</p>
                        <h2>Engineering decisions.</h2>
                        <p>{{ $project->solution }}</p>
                    </section>
                @endif

                <section id="technology" class="case-section" data-case-section>
                    <p class="system-label">04 / Deployed technologies</p>
                    <h2>Technology nodes.</h2>
                    <div class="technology-stream">
                        @forelse ($project->skills as $skill)
                            <span>{{ strtoupper($skill->name) }}</span>
                        @empty
                            <span>WEB SYSTEM</span>
                        @endforelse
                    </div>
                </section>

                @if ($project->result)
                    <section id="result" class="case-section" data-case-section>
                        <p class="system-label">05 / Result</p>
                        <h2>Documented outcome.</h2>
                        <p>{{ $project->result }}</p>
                    </section>
                @endif
            </div>
        </div>

        @if ($project->media->isNotEmpty())
            <section id="gallery" class="case-gallery" data-case-section>
                <div class="container">
                    <header class="section-heading">
                        <div>
                            <p class="system-label">06 / Media records</p>
                            <h2>Project gallery</h2>
                        </div>
                        <span>{{ $project->media->count() }} assets</span>
                    </header>
                    <div class="case-gallery__grid">
                        @foreach ($project->media as $media)
                            <figure>
                                @if ($media->media_type === 'video')
                                    <video
                                        controls
                                        preload="metadata"
                                        aria-label="{{ $media->alt_text ?: 'Video '.$project->title }}"
                                    >
                                        <source src="{{ $media->path }}">
                                    </video>
                                @else
                                    <img
                                        src="{{ $media->path }}"
                                        alt="{{ $media->alt_text ?: 'Dokumentasi '.$project->title }}"
                                        width="{{ $media->width ?: 1200 }}"
                                        height="{{ $media->height ?: 800 }}"
                                        loading="lazy"
                                    >
                                @endif
                                @if ($media->caption)
                                    <figcaption>{{ $media->caption }}</figcaption>
                                @endif
                            </figure>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <footer class="next-case">
            <div class="container">
                @if ($nextProject)
                    <p class="system-label">Next case</p>
                    <a href="{{ route('projects.show', $nextProject) }}">
                        <span>{{ str_pad($nextProject->sort_order + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <strong>{{ $nextProject->title }}</strong>
                        <small>{{ $nextProject->categories->pluck('name')->join(' / ') }}</small>
                        <svg aria-hidden="true" viewBox="0 0 24 24">
                            <path d="M5 12h14M14 7l5 5-5 5" />
                        </svg>
                    </a>
                @else
                    <p class="system-label">Archive complete</p>
                    <a href="{{ route('projects.index') }}">
                        <strong>Return to project archive</strong>
                        <svg aria-hidden="true" viewBox="0 0 24 24">
                            <path d="M5 12h14M14 7l5 5-5 5" />
                        </svg>
                    </a>
                @endif
            </div>
        </footer>
    </article>
@endsection
