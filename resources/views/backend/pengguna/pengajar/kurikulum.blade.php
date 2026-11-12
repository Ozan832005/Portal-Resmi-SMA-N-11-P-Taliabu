@extends('layouts.backend.app')

@section('title', 'Kurikulum')

@section('content')
    <div class="km-page">
        <header class="km-page-head">
            <div>
                <h1 class="km-page-title">Kurikulum</h1>
                <p class="km-page-sub">Materi kurikulum dan modul ajar untuk pengajar.</p>
            </div>
        </header>

        @forelse ($curricula as $curriculum)
            <section class="km-panel mb-2">
                <div class="km-panel-head">
                    <h2 class="km-panel-title">{{ $curriculum->label }} · {{ $curriculum->title }}</h2>
                </div>
                <p>{{ $curriculum->description }}</p>

                @if ($curriculum->module_ajar_path)
                    <a class="btn btn-primary" href="{{ route('backend-kurikulum.modul-ajar', $curriculum) }}">
                        <i data-feather="download" aria-hidden="true"></i> Unduh Modul Ajar (PDF)
                    </a>
                @endif
            </section>
        @empty
            <section class="km-panel">
                <p class="mb-0 text-muted">Materi kurikulum belum tersedia.</p>
            </section>
        @endforelse
    </div>
@endsection
