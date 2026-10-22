@extends('layouts.app')

@php
    $title = 'Privacy Protocol — '.config('portfolio.owner_name');
@endphp

@section('content')
    <header class="page-command container">
        <p class="system-label">Protocol / Privacy</p>
        <div class="page-command__grid">
            <h1>Your data,<br><em>handled intentionally.</em></h1>
            <p>This page documents the limited information handled by this portfolio.</p>
        </div>
    </header>
    <section class="runtime-section">
        <div class="container policy-console">
            <section>
                <span>01</span>
                <div>
                    <h2>Stored information</h2>
                    <p>
                        The contact form stores name, email, subject, message, and an IP hash for
                        spam protection. Data is used only to respond to your communication.
                    </p>
                </div>
            </section>
            <section>
                <span>02</span>
                <div>
                    <h2>Media delivery</h2>
                    <p>
                        Portfolio media is delivered through Cloudinary. Cloudinary and database
                        credentials are never sent to the browser.
                    </p>
                </div>
            </section>
            <section>
                <span>03</span>
                <div>
                    <h2>Deletion</h2>
                    <p>You may request deletion by contacting the portfolio owner.</p>
                </div>
            </section>
        </div>
    </section>
@endsection
