@extends('layouts.backend.app')

@section('title', 'Kurikulum')

@section('content')
    <div class="km-page">
        <header class="km-page-head">
            <div>
                <h1 class="km-page-title">Kurikulum</h1>
                <p class="km-page-sub">Kelola materi yang ditampilkan pada halaman Kurikulum situs sekolah.</p>
            </div>
            <a class="btn btn-primary" href="{{ route('backend-kurikulum.create') }}">
                <i data-feather="plus" aria-hidden="true"></i> Tambah kurikulum
            </a>
        </header>

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif

        <section class="km-panel">
            <div class="km-panel-head">
                <h2 class="km-panel-title">Daftar kurikulum</h2>
                <span class="km-figure-note mb-0">{{ $curricula->count() }} materi</span>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Urutan</th>
                            <th>Label</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($curricula as $curriculum)
                            <tr>
                                <td>{{ $curriculum->sort_order }}</td>
                                <td>{{ $curriculum->label }}</td>
                                <td>{{ $curriculum->title }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($curriculum->description, 110) }}</td>
                                <td>
                                    <span class="badge badge-light-{{ $curriculum->is_active ? 'success' : 'secondary' }}">
                                        {{ $curriculum->is_active ? 'Aktif' : 'Tidak aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a class="btn btn-sm btn-outline-primary mr-1" href="{{ route('backend-kurikulum.edit', $curriculum) }}">Edit</a>
                                        <form action="{{ route('backend-kurikulum.destroy', $curriculum) }}" method="post"
                                              data-delete-message="{{ 'Materi kurikulum “'.$curriculum->title.'” akan dihapus permanen.' }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted py-3" colspan="6">Belum ada materi kurikulum.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
