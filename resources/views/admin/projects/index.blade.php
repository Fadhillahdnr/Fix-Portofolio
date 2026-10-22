@extends('layouts.admin')

@section('heading', 'Proyek')

@section('content')
    <div class="panel-actions">
        <p>Kelola draft, publikasi, dan media proyek.</p>
        <a class="btn primary" href="{{ route('admin.projects.create') }}">
            Tambah proyek
        </a>
    </div>

    <section class="admin-panel">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Unggulan</th>
                        <th>Diperbarui</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr>
                            <td>
                                <strong>{{ $project->title }}</strong><br>
                                <small>/{{ $project->slug }}</small>
                            </td>
                            <td><span class="badge">{{ $project->status }}</span></td>
                            <td>{{ $project->is_featured ? 'Ya' : '—' }}</td>
                            <td>{{ $project->updated_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.projects.edit', $project) }}">Edit ↗</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">Belum ada proyek.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $projects->links() }}
    </section>
@endsection
