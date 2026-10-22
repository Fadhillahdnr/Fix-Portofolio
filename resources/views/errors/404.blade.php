@extends('layouts.app')
@section('content')
    <section class="system-error container">
        <p class="system-label">NODE_NOT_FOUND</p>
        <strong>404</strong>
        <h1>The requested route does not exist in this archive.</h1>
        <a class="button button--primary" href="{{ route('home') }}">Return to runtime</a>
    </section>
@endsection
