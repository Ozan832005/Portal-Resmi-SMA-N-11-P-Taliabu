@extends('layouts.backend.app')

@section('title', 'Video publikasi')

@section('content')
    <div class="km-page">
        <header class="km-page-head">
            <div>
                <h1 class="km-page-title">Video publikasi</h1>
                <p class="km-page-sub">Kelola video yang tampil pada halaman Publikasi secara terpisah dari video halaman situs.</p>
            </div>
            <a class="btn btn-primary" href="{{ route('backend-video-publikasi.create') }}">
                <i data-feather="plus" aria-hidden="true"></i> Tambah video
            </a>
        </header>

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif

        <section class="km-panel">
            <div class="km-panel-head">
                <h2 class="km-panel-title">Daftar video publikasi</h2>
                <span class="km-figure-note mb-0">{{ $videos->count() }} video</span>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>URL video</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($videos as $video)
                            <tr>
                                <td>{{ $video->title }}</td>
                                <td class="text-truncate" style="max-width: 360px">
                                    <a href="{{ $video->url }}" target="_blank" rel="noopener">{{ $video->url }}</a>
                                </td>
                                <td>
                                    <span class="badge badge-light-{{ $video->is_active === '0' ? 'success' : 'secondary' }}">
                                        {{ $video->is_active === '0' ? 'Aktif' : 'Tidak aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a class="btn btn-sm btn-outline-primary mr-1" href="{{ route('backend-video-publikasi.edit', $video) }}">Edit</a>
                                        <form action="{{ route('backend-video-publikasi.destroy', $video) }}" method="post"
                                              data-delete-message="{{ 'Video publikasi “'.$video->title.'” akan dihapus permanen.' }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted py-3" colspan="4">Belum ada video publikasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
