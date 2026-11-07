@extends('layouts.backend.app')

@section('title')
    Staf Sekolah
@endsection

@section('content')
@php
    $daftarStaf = $staf ?? collect();
    $stafAktif = $daftarStaf->where('status', 'Aktif')->count();
@endphp

<div class="km-page">
    <header class="km-page-head">
        <div>
            <h1 class="km-page-title">Staf sekolah</h1>
            <p class="km-page-sub">
                {{ $daftarStaf->count() }} akun staf, {{ $stafAktif }} di antaranya aktif.
                Staf membantu administrasi sekolah dan tidak memiliki akses pengaturan.
            </p>
        </div>
        <a href="{{ route('backend-pengguna-staf.create') }}" class="btn btn-primary">Tambah staf</a>
    </header>

    @if ($message = Session::get('success'))
        <div class="alert alert-success" role="alert">{{ $message }}</div>
    @elseif ($message = Session::get('error'))
        <div class="alert alert-danger" role="alert">{{ $message }}</div>
    @endif

    <section class="km-panel">
        @if ($daftarStaf->isEmpty())
            <div class="km-empty">
                <p class="km-empty-title">Belum ada staf</p>
                <p class="km-empty-text">Tambahkan akun staf untuk membantu pengelolaan administrasi sekolah.</p>
                <a href="{{ route('backend-pengguna-staf.create') }}" class="btn btn-primary btn-sm">Tambah staf</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="dt-responsive table">
                    <thead>
                        <tr>
                            <th scope="col">Staf</th>
                            <th scope="col">NIP</th>
                            <th scope="col">Status</th>
                            <th scope="col">Bergabung</th>
                            <th scope="col" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarStaf as $item)
                            @php
                                $inisial = collect(explode(' ', trim((string) $item->name)))
                                    ->filter()
                                    ->take(2)
                                    ->map(fn ($kata) => mb_strtoupper(mb_substr($kata, 0, 1)))
                                    ->implode('');
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($item->foto_profile)
                                            <img src="{{ asset('storage/images/profile/'.$item->foto_profile) }}"
                                                 alt="Foto {{ $item->name }}" class="km-avatar mr-2">
                                        @else
                                            <span class="km-avatar km-avatar--initials mr-2">{{ $inisial ?: '?' }}</span>
                                        @endif
                                        <div class="km-person">
                                            <span class="km-person-name">{{ $item->name }}</span>
                                            <span class="km-person-meta">{{ $item->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->userDetail->nip ?? 'Belum diisi' }}</td>
                                <td>
                                    <span class="km-status {{ $item->status == 'Aktif' ? 'km-status--live' : 'km-status--draft' }}">
                                        {{ $item->status ?: 'Belum diatur' }}
                                    </span>
                                </td>
                                <td>{{ $item->created_at ? $item->created_at->locale('id')->translatedFormat('j M Y') : 'Tidak diketahui' }}</td>
                                <td class="text-right">
                                    <div class="km-row-actions">
                                        <a href="{{ route('backend-pengguna-staf.edit', $item->id) }}" class="btn btn-sm btn-success">Edit</a>
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
