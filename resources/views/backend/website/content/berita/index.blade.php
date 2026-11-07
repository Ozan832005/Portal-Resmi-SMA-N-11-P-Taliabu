@extends('layouts.backend.app')

@section('title')
    Berita
@endsection

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success" role="alert">
            <div class="alert-body">
                <strong>{{ $message }}</strong>
                <button type="button" class="close" data-dismiss="alert">×</button>
            </div>
        </div>
    @elseif($message = Session::get('error'))
        <div class="alert alert-danger" role="alert">
            <div class="alert-body">
                <strong>{{ $message }}</strong>
                <button type="button" class="close" data-dismiss="alert">×</button>
            </div>
        </div>
    @endif

<div class="km-page">
    <header class="km-page-head">
        <div>
            <h1 class="km-page-title">Berita</h1>
            <p class="km-page-sub">{{ $berita->count() }} berita tersimpan. Berita berstatus terbit tampil pada situs sekolah.</p>
        </div>
        <a href="{{ route('backend-berita.create') }}" class="btn btn-primary">Tambah berita</a>
    </header>

    @if ($kategori->isEmpty())
        <div class="km-empty mb-2">
            <p class="km-empty-title">Kategori berita belum ada</p>
            <p class="km-empty-text">Buat kategori terlebih dahulu sebelum menambahkan berita.</p>
            <a href="{{ route('backend-kategori-berita.index') }}" class="btn btn-primary btn-sm">Kelola kategori berita</a>
        </div>
    @endif

    <section class="km-panel">
        @if ($berita->isEmpty())
            <div class="km-empty">
                <p class="km-empty-title">Belum ada berita</p>
                <p class="km-empty-text">Mulai dengan menulis berita pertama Anda.</p>
                <a href="{{ route('backend-berita.create') }}" class="btn btn-primary btn-sm">Tambah berita</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="dt-responsive table">
                    <thead>
                        <tr>
                            <th scope="col">Berita</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($berita as $beritas)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($beritas->thumbnail)
                                            <img src="{{ asset('storage/images/berita/'.$beritas->thumbnail) }}"
                                                 alt="Thumbnail {{ $beritas->title }}" class="km-thumb mr-2">
                                        @else
                                            <span class="km-thumb km-thumb--empty mr-2">Tanpa gambar</span>
                                        @endif
                                        <span>{{ $beritas->title }}</span>
                                    </div>
                                </td>
                                <td>{{ $beritas->kategori->nama ?? 'Tanpa kategori' }}</td>
                                <td>
                                    <span class="km-status {{ $beritas->is_active == '0' ? 'km-status--live' : 'km-status--draft' }}">
                                        {{ $beritas->is_active == '0' ? 'Terbit' : 'Draf' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="km-row-actions">
                                        <a href="{{ route('backend-berita.edit', $beritas->id) }}" class="btn btn-sm btn-success">Edit</a>
                                        <form action="{{ route('backend-berita.destroy', $beritas->id) }}" method="post"
                                              onsubmit="return confirm('Hapus berita {{ addslashes($beritas->title) }}? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm km-btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</div>
@endsection
