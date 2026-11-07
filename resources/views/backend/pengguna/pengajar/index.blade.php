@extends('layouts.backend.app')

@section('title')
    Pengajar
@endsection

@section('content')
@php
    $daftarPengajar = $pengajar ?? collect();
    $pengajarAktif = $daftarPengajar->where('status', 'Aktif')->count();
@endphp

<div class="km-page">
    <header class="km-page-head">
        <div>
            <h1 class="km-page-title">Pengajar</h1>
            <p class="km-page-sub">
                {{ $daftarPengajar->count() }} akun pengajar, {{ $pengajarAktif }} di antaranya aktif.
                Akun aktif bisa masuk ke dashboard sesuai hak aksesnya.
            </p>
        </div>
        <a href="{{ route('backend-pengguna-pengajar.create') }}" class="btn btn-primary">Tambah pengajar</a>
    </header>

    @if ($message = Session::get('success'))
        <div class="alert alert-success" role="alert">{{ $message }}</div>
    @elseif ($message = Session::get('error'))
        <div class="alert alert-danger" role="alert">{{ $message }}</div>
    @endif

    <section class="km-panel">
        @if ($daftarPengajar->isEmpty())
            <div class="km-empty">
                <p class="km-empty-title">Belum ada pengajar</p>
                <p class="km-empty-text">Tambahkan akun pengajar agar mereka bisa mengelola kelas dan melihat data murid.</p>
                <a href="{{ route('backend-pengguna-pengajar.create') }}" class="btn btn-primary btn-sm">Tambah pengajar</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="dt-responsive table">
                    <thead>
                        <tr>
                            <th scope="col">Pengajar</th>
                            <th scope="col">Mata pelajaran</th>
                            <th scope="col">NIP</th>
                            <th scope="col">Status</th>
                            <th scope="col">Bergabung</th>
                            <th scope="col" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarPengajar as $item)
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
                                <td>{{ $item->userDetail->mengajar ?? 'Belum diisi' }}</td>
                                <td>{{ $item->userDetail->nip ?? 'Belum diisi' }}</td>
                                <td>
                                    <span class="km-status {{ $item->status == 'Aktif' ? 'km-status--live' : 'km-status--draft' }}">
                                        {{ $item->status ?: 'Belum diatur' }}
                                    </span>
                                </td>
                                <td>{{ $item->created_at ? $item->created_at->locale('id')->translatedFormat('j M Y') : 'Tidak diketahui' }}</td>
                                <td class="text-right">
                                    <div class="km-row-actions">
                                        <a href="{{ route('backend-pengguna-pengajar.edit', $item->id) }}" class="btn btn-sm btn-success">Edit</a>
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
