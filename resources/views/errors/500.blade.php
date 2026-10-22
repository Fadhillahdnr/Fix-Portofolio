@extends('layouts.app')
@section('content')
    <section class="system-error container">
        <p class="system-label">RUNTIME_EXCEPTION</p>
        <strong>500</strong>
        <h1>The interface encountered a temporary internal error.</h1>
        <a class="button button--primary" href="{{ route('home') }}">Return to runtime</a>
    </section>
@endsection
