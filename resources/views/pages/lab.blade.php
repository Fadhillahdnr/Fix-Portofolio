@extends('layouts.app')

@php
    $title = 'Engineering Lab — '.config('portfolio.owner_name');
    $description = 'Technical research tracks and selected IoT, embedded, data, and AI records.';
@endphp

@section('content')
    <header class="page-command container">
        <p class="system-label">Directory / 04 / Engineering lab</p>
        <div class="page-command__grid">
            <h1>Prototype, observe,<br><em>iterate, document.</em></h1>
            <p>
                The lab indexes experimental domains and smaller technical directions. It only
                surfaces records already present in the portfolio database.
            </p>
        </div>
    </header>

    <section class="runtime-section lab-section">
        <div class="container">
            <header class="section-heading reveal">
                <div>
                    <p class="system-label">01 / Research tracks</p>
                    <h2>Active capability nodes</h2>
                </div>
                <span>{{ $skillSectors->count() }} sectors indexed</span>
            </header>
            <div class="lab-grid">
                @foreach ($skillSectors as $category => $skills)
                    <article class="lab-node reveal">
                        <header>
                            <span>LAB_{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}</span>
                            <i>TRACKED</i>
                        </header>
                        <h3>{{ $category }}</h3>
                        <p>{{ $skills->pluck('name')->join(' · ') }}</p>
                        <div class="lab-node__signal" aria-hidden="true">
                            @foreach (range(1, 8) as $signal)
                                <span style="--signal: {{ $signal }}"></span>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="runtime-section archive-section">
        <div class="container">
            <header class="section-heading reveal">
                <div>
                    <p class="system-label">02 / Related records</p>
                    <h2>Experimental cases</h2>
                </div>
            </header>
            <div class="archive-list">
                @forelse ($projects as $project)
                    <x-project-card :project="$project" />
                @empty
                    <div class="system-empty">
                        <span>LAB_RECORDS_PENDING</span>
                        <p>
                            No project is explicitly classified as a lab record yet. Capability
                            tracks remain available above without fabricating experiments.
                        </p>
                        <a href="{{ route('projects.index') }}">Open the complete project archive</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
