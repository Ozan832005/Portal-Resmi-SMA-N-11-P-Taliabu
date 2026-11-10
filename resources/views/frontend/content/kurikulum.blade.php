@extends('layouts.Frontend.app')

@section('title')
    {{ __('Kurikulum') }}
@endsection

@section('content')
    @section('about')
        <div class="km-profile-shell">
            <div class="container">
                <div class="km-page-head">
                    <span class="km-eyebrow"><i class="fa fa-book" aria-hidden="true"></i> {{ __('Kurikulum') }}</span>
                    <h1>{{ __('Perjalanan pembelajaran yang terarah') }}</h1>
                </div>

                <div class="km-profile-layout">
                    <div class="km-profile-content">
                        <p>{{ __('Kurukulum sekolah disusun untuk memastikan proses belajar mengajar berjalan terstruktur, menyenangkan, dan berpihak pada kebutuhan siswa. Dalam setiap mata pelajaran, guru menyusun CP, ATP, TP, dan modul ajar sebagai panduan pelaksanaan pembelajaran yang terukur.') }}</p>
                        <div class="km-goal-stack">
                            @foreach ($curriculum as $item)
                                <div class="km-goal-card">
                                    <h3>{{ $item->label }} · {{ __($item->title) }}</h3>
                                    <p>{{ __($item->description) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="km-profile-visual">
                        <img src="{{ asset('Assets/Frontend/img/about/1.jpg') }}" alt="Kurikulum SMA Negeri 11 Pulau Taliabu">
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('guru')
        @include('frontend.content.guru')
    @endsection
@endsection
