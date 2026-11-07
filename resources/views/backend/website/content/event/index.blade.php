@extends('layouts.backend.app')

@section('title')
    Event
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
            <h1 class="km-page-title">Event</h1>
            <p class="km-page-sub">{{ $event->count() }} acara tersimpan. Acara berstatus aktif tampil pada dashboard dan situs sekolah.</p>
        </div>
        <a href="{{ route('backend-event.create') }}" class="btn btn-primary">Tambah event</a>
    </header>

    <section class="km-panel">
        @if ($event->isEmpty())
            <div class="km-empty">
                <p class="km-empty-title">Belum ada event</p>
                <p class="km-empty-text">Tambahkan acara sekolah agar muncul pada dashboard.</p>
                <a href="{{ route('backend-event.create') }}" class="btn btn-primary btn-sm">Tambah event</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="dt-responsive table">
                    <thead>
                        <tr>
                            <th scope="col">Event</th>
                            <th scope="col">Lokasi</th>
                            <th scope="col">Waktu</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($event as $events)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($events->thumbnail)
                                            <img src="{{ asset('storage/images/event/'.$events->thumbnail) }}"
                                                 alt="Thumbnail {{ $events->title }}" class="km-thumb mr-2">
                                        @else
                                            <span class="km-thumb km-thumb--empty mr-2">Tanpa gambar</span>
                                        @endif
                                        <span>{{ $events->title }}</span>
                                    </div>
                                </td>
                                <td>{{ $events->lokasi ?: 'Lokasi belum diisi' }}</td>
                                <td>
                                    {{ $events->acara ? \Carbon\Carbon::parse($events->acara)->locale('id')->translatedFormat('j M Y, H.i') : 'Belum ditentukan' }}
                                </td>
                                <td>
                                    <span class="km-status {{ $events->is_active == '0' ? 'km-status--live' : 'km-status--draft' }}">
                                        {{ $events->is_active == '0' ? 'Aktif' : 'Tidak aktif' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="km-row-actions">
                                        <a href="{{ route('backend-event.edit', $events->id) }}" class="btn btn-sm btn-success">Edit</a>
                                        <form action="{{ route('backend-event.destroy', $events->id) }}" method="post"
                                              onsubmit="return confirm('Hapus event {{ addslashes($events->title) }}? Tindakan ini tidak dapat dibatalkan.');">
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
