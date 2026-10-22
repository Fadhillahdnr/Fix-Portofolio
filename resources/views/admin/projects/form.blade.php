@extends('layouts.admin')

@section('heading', $project->exists ? 'Edit proyek' : 'Proyek baru')

@section('content')
    @php
        $selectedCategories = old(
            'categories',
            $project->categories?->pluck('id')->all() ?? [],
        );
        $selectedSkills = old(
            'skills',
            $project->skills?->pluck('id')->all() ?? [],
        );
    @endphp

    <form
        class="admin-form"
        method="post"
        enctype="multipart/form-data"
        action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
    >
        @csrf
        @if ($project->exists)
            @method('put')
        @endif

        <div class="form-columns">
            <section class="admin-panel">
                <h2>Informasi utama</h2>
                <label>
                    Judul
                    <input name="title" value="{{ old('title', $project->title) }}" required>
                </label>
                <label>
                    Slug
                    <input name="slug" value="{{ old('slug', $project->slug) }}" required>
                </label>
                <label>
                    Ringkasan
                    <textarea name="summary" rows="4" required>{{ old('summary', $project->summary) }}</textarea>
                </label>
                <label>
                    Tantangan
                    <textarea name="problem" rows="6">{{ old('problem', $project->problem) }}</textarea>
                </label>
                <label>
                    Solusi
                    <textarea name="solution" rows="6">{{ old('solution', $project->solution) }}</textarea>
                </label>
                <label>
                    Hasil
                    <textarea name="result" rows="6">{{ old('result', $project->result) }}</textarea>
                </label>
            </section>

            <aside class="admin-panel">
                <h2>Publikasi</h2>
                <label>
                    Status
                    <select name="status">
                        <option
                            value="draft"
                            @selected(old('status', $project->status) === 'draft')
                        >
                            Draft
                        </option>
                        <option
                            value="published"
                            @selected(old('status', $project->status) === 'published')
                        >
                            Published
                        </option>
                    </select>
                </label>
                <label>
                    Tanggal publikasi
                    <input
                        type="datetime-local"
                        name="published_at"
                        value="{{ old('published_at', $project->published_at?->format('Y-m-d\TH:i')) }}"
                    >
                </label>
                <label class="check">
                    <input
                        type="checkbox"
                        name="is_featured"
                        value="1"
                        @checked(old('is_featured', $project->is_featured))
                    >
                    Proyek unggulan
                </label>
                <label>
                    Urutan
                    <input
                        type="number"
                        min="0"
                        name="sort_order"
                        value="{{ old('sort_order', $project->sort_order ?? 0) }}"
                    >
                </label>
                <label>
                    Peran
                    <input name="role" value="{{ old('role', $project->role) }}">
                </label>
                <label>
                    Demo URL
                    <input
                        type="url"
                        name="demo_url"
                        value="{{ old('demo_url', $project->demo_url) }}"
                    >
                </label>
                <label>
                    Repository URL
                    <input
                        type="url"
                        name="repository_url"
                        value="{{ old('repository_url', $project->repository_url) }}"
                    >
                </label>
                <label>
                    Sampul (JPG/PNG/WebP/AVIF, maks. 8MB)
                    <input
                        type="file"
                        name="cover"
                        accept="image/jpeg,image/png,image/webp,image/avif"
                    >
                </label>
                <fieldset>
                    <legend>Kategori</legend>
                    @foreach ($categories as $item)
                        <label class="check">
                            <input
                                type="checkbox"
                                name="categories[]"
                                value="{{ $item->id }}"
                                @checked(in_array($item->id, $selectedCategories))
                            >
                            {{ $item->name }}
                        </label>
                    @endforeach
                </fieldset>
                <fieldset>
                    <legend>Skill</legend>
                    @foreach ($skills as $item)
                        <label class="check">
                            <input
                                type="checkbox"
                                name="skills[]"
                                value="{{ $item->id }}"
                                @checked(in_array($item->id, $selectedSkills))
                            >
                            {{ $item->name }}
                        </label>
                    @endforeach
                </fieldset>
                <button class="btn primary" type="submit">Simpan proyek</button>
            </aside>
        </div>
    </form>

    @if ($project->exists)
        <section class="admin-panel">
            <h2>Galeri Cloudinary</h2>
            <form
                class="inline-upload"
                method="post"
                enctype="multipart/form-data"
                action="{{ route('admin.projects.media.store', $project) }}"
            >
                @csrf
                <label>
                    File
                    <input
                        type="file"
                        name="file"
                        required
                        accept="image/jpeg,image/png,image/webp,image/avif,video/mp4,video/webm"
                    >
                </label>
                <label>Alt text<input name="alt_text" required></label>
                <label>Caption<input name="caption"></label>
                <button class="btn primary" type="submit">Upload</button>
            </form>

            <div class="media-admin-grid">
                @foreach ($project->media as $media)
                    <article>
                        @if ($media->media_type === 'video')
                            <video src="{{ $media->path }}" controls></video>
                        @else
                            <img src="{{ $media->path }}" alt="{{ $media->alt_text }}">
                        @endif
                        <p>{{ $media->alt_text }}</p>
                        <form
                            method="post"
                            action="{{ route('admin.projects.media.destroy', [$project, $media]) }}"
                            onsubmit="return confirm('Hapus media ini?')"
                        >
                            @csrf
                            @method('delete')
                            <button class="danger-link" type="submit">Hapus</button>
                        </form>
                    </article>
                @endforeach
            </div>
        </section>

        <form
            method="post"
            action="{{ route('admin.projects.destroy', $project) }}"
            onsubmit="return confirm('Hapus proyek beserta galerinya?')"
        >
            @csrf
            @method('delete')
            <button class="danger-link" type="submit">Hapus proyek permanen</button>
        </form>
    @endif
@endsection
