@extends('layouts.app')
@section('content')
    <section class="system-error container">
        <p class="system-label">SESSION_EXPIRED</p>
        <strong>419</strong>
        <h1>The security session ended. Reload the interface and try again.</h1>
        <a class="button button--primary" href="{{ url()->previous() }}">Return</a>
    </section>
@endsection
