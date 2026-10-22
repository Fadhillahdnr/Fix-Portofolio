@extends('layouts.admin')

@section('heading', 'Ringkasan')

@section('content')
    <div class="stats-grid">
        @foreach ($stats as $label => $value)
            <article>
                <span>{{ $label }}</span>
                <strong>{{ $value }}</strong>
            </article>
        @endforeach
    </div>

    <section class="admin-panel">
        <div class="panel-head">
            <h2>Pesan terbaru</h2>
            <a href="{{ route('admin.content') }}">Kelola semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Subjek</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recent as $message)
                        <tr>
                            <td>{{ $message->name }}</td>
                            <td>{{ $message->subject }}</td>
                            <td><span class="badge">{{ $message->status }}</span></td>
                            <td>{{ $message->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">Belum ada pesan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
