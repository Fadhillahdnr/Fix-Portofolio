@extends('layouts.admin')

@section('heading', 'Konten & pesan')

@section('content')
    <section class="admin-panel">
        <h2>Profil</h2>
        <form
            class="form-grid"
            method="post"
            action="{{ route('admin.content.profile') }}"
        >
            @csrf
            @method('put')
            <label>
                Headline
                <input
                    name="headline"
                    value="{{ old('headline', $profile?->headline) }}"
                    required
                >
            </label>
            <label>
                Lokasi
                <input name="location" value="{{ old('location', $profile?->location) }}">
            </label>
            <label class="full">
                Bio singkat
                <textarea name="short_bio" required>{{ old('short_bio', $profile?->short_bio) }}</textarea>
            </label>
            <label class="full">
                Bio lengkap
                <textarea name="full_bio" rows="5">{{ old('full_bio', $profile?->full_bio) }}</textarea>
            </label>
            <label>
                Ketersediaan
                <input
                    name="availability"
                    value="{{ old('availability', $profile?->availability) }}"
                >
            </label>
            <button class="btn primary" type="submit">Simpan profil</button>
        </form>
    </section>

    @php
        $forms = [
            ['category', 'Kategori', ['name' => 'Nama', 'slug' => 'Slug opsional']],
            [
                'skill',
                'Skill',
                [
                    'name' => 'Nama',
                    'category' => 'Kelompok',
                    'proficiency' => 'Proficiency 0–100',
                ],
            ],
            ['service', 'Layanan', ['title' => 'Judul', 'description' => 'Deskripsi']],
            [
                'experience',
                'Pengalaman',
                [
                    'company' => 'Perusahaan',
                    'position' => 'Posisi',
                    'description' => 'Deskripsi',
                    'start_date' => 'Tanggal mulai',
                ],
            ],
            [
                'social',
                'Social link',
                ['platform' => 'Platform', 'label' => 'Label', 'url' => 'URL'],
            ],
            [
                'setting',
                'Pengaturan',
                ['key' => 'Key', 'value' => 'Value', 'group' => 'Group'],
            ],
        ];
    @endphp

    <div class="content-grid">
        @foreach ($forms as [$type, $heading, $fields])
            <section class="admin-panel">
                <h2>{{ $heading }}</h2>
                <form method="post" action="{{ route('admin.content.'.$type) }}">
                    @csrf
                    @foreach ($fields as $name => $label)
                        <label>
                            {{ $label }}
                            @if (str_contains($name, 'description'))
                                <textarea name="{{ $name }}" required></textarea>
                            @else
                                <input
                                    name="{{ $name }}"
                                    @if ($name === 'start_date')
                                        type="date"
                                    @endif
                                    @if ($name === 'url')
                                        type="url"
                                    @endif
                                    required
                                >
                            @endif
                        </label>
                    @endforeach
                    <button class="btn ghost" type="submit">Tambah</button>
                </form>
            </section>
        @endforeach
    </div>

    <section class="admin-panel">
        <div class="panel-head">
            <h2>Pesan masuk</h2>
            <span>{{ $messages->total() }} pesan</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Pengirim</th>
                        <th>Pesan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $message)
                        <tr>
                            <td>
                                <strong>{{ $message->name }}</strong><br>
                                <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                            </td>
                            <td>
                                <strong>{{ $message->subject }}</strong>
                                <details>
                                    <summary>Baca pesan</summary>
                                    <p>{{ $message->message }}</p>
                                </details>
                            </td>
                            <td>
                                <form
                                    method="post"
                                    action="{{ route('admin.messages.update', $message) }}"
                                >
                                    @csrf
                                    @method('patch')
                                    <select name="status" onchange="this.form.submit()">
                                        @foreach (['new', 'read', 'archived'] as $status)
                                            <option @selected($message->status === $status)>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3">Belum ada pesan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $messages->links() }}
    </section>
@endsection
