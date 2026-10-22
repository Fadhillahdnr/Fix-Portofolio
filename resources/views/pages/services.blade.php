@extends('layouts.app')

@php
    $title = 'Engineering Capabilities — '.config('portfolio.owner_name');
@endphp

@section('content')
    <header class="page-command container">
        <p class="system-label">Capability registry</p>
        <div class="page-command__grid">
            <h1>From requirements<br><em>to integrated systems.</em></h1>
            <p>
                Backend Java and Laravel, fullstack interfaces, database engineering, IoT
                dashboards, and deployment through a structured engineering process.
            </p>
        </div>
    </header>

    <section class="runtime-section">
        <div class="container capability-registry">
            @foreach ($services as $service)
                <article class="reveal">
                    <span>CAP_{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <h2>{{ $service->title }}</h2>
                    <p>{{ $service->description }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="runtime-section process-section">
        <div class="container split-console">
            <header>
                <p class="system-label">Execution protocol</p>
                <h2>Engineering workflow</h2>
            </header>
            <ol class="process-list">
                <li>
                    <span>01</span>
                    <strong>Understand the domain</strong>
                    <p>Map users, processes, data, devices, and constraints.</p>
                </li>
                <li>
                    <span>02</span>
                    <strong>Design the architecture</strong>
                    <p>Separate interface, API, service, repository, database, and integrations.</p>
                </li>
                <li>
                    <span>03</span>
                    <strong>Implement in stages</strong>
                    <p>
                        Build core flows, authentication, validation, and integrations with fast
                        feedback.
                    </p>
                </li>
                <li>
                    <span>04</span>
                    <strong>Test and troubleshoot</strong>
                    <p>Inspect runtime, queries, environments, deployment, and root causes.</p>
                </li>
            </ol>
        </div>
    </section>
@endsection
