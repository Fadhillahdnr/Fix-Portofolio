@extends('layouts.app')

@php
    $title = 'Project Archive — '.config('portfolio.owner_name');
    $description = 'Arsip engineering case study fullstack, Java, Laravel, IoT, dan AI/ML.';
@endphp

@section('content')
    <header class="page-command container">
        <p class="system-label">Directory / 03 / Project archive</p>
        <div class="page-command__grid">
            <h1>Systems, experiments,<br><em>and technical records.</em></h1>
            <p>
                A searchable archive of systems I have designed, implemented, integrated, or
                troubleshot across software, data, devices, and applied intelligence.
            </p>
        </div>
    </header>

    <section class="archive-controls">
        <div class="container archive-controls__inner">
            <form method="get" action="{{ route('projects.index') }}">
                <label for="project-search">Search archive</label>
                <div>
                    <span aria-hidden="true">&gt;</span>
                    <input
                        id="project-search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Project title or keyword"
                    >
                    <button type="submit">Execute</button>
                </div>
            </form>
            <div class="archive-filters" aria-label="Filter kategori proyek">
                <a
                    href="{{ route('projects.index', ['q' => request('q')]) }}"
                    @class(['is-active' => ! request('category')])
                >
                    All
                </a>
                @foreach ($categories as $category)
                    <a
                        href="{{ route('projects.index', [
                            'category' => $category->slug,
                            'q' => request('q'),
                        ]) }}"
                        @class(['is-active' => request('category') === $category->slug])
                    >
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="runtime-section archive-section archive-section--index">
        <div class="container">
            <div class="archive-index-head">
                <span>{{ str_pad($projects->total(), 2, '0', STR_PAD_LEFT) }} records found</span>
                <span>Sorted / curated index</span>
            </div>
            <div class="archive-list">
                @forelse ($projects as $project)
                    <x-project-card :project="$project" />
                @empty
                    <div class="system-empty">
                        <span>NO_RECORDS_FOUND</span>
                        <p>Change the search query or return to the complete project archive.</p>
                        <a href="{{ route('projects.index') }}">Reset archive query</a>
                    </div>
                @endforelse
            </div>
            <div class="runtime-pagination">{{ $projects->links() }}</div>
        </div>
    </section>
@endsection
