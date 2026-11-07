@extends('layouts.Frontend.app')

@section('title')
    {{ __('Visi dan Misi') }}
@endsection

@section('content')
    @section('about')
        <div class="km-vision-shell">
            <div class="container">
                @if ($visimisi)
                    <div class="km-page-head">
                        <span class="km-eyebrow"><i class="fa fa-bullseye" aria-hidden="true"></i> {{ __('Visi & Misi') }}</span>
                        <h1>{{ __('Komitmen pendidikan SMA Negeri 11 Pulau Taliabu') }}</h1>
                    </div>
                    <div class="km-vision-layout">
                        <div class="km-vision-content">
                            <div class="km-goal-stack">
                                <div class="km-goal-card">
                                    <h3>{{ __('Visi') }}</h3>
                                    <p>{{ $visimisi->visi }}</p>
                                </div>
                                <div class="km-goal-card">
                                    <h3>{{ __('Misi') }}</h3>
                                    <p>{{ $visimisi->misi }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="km-vision-visual">
                            <img src="{{ asset('storage/images/visimisi/' . $visimisi->image) }}" alt="Visi dan Misi SMA Negeri 11 Pulau Taliabu">
                        </div>
                    </div>
                @else
                    <div class="km-empty">
                        <i class="fa fa-bullseye" aria-hidden="true"></i>
                        <strong>{{ __('Visi dan misi belum tersedia') }}</strong>
                        <span>{{ __('Data visi dan misi sekolah akan tampil di sini.') }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endsection

    @section('guru')
        @include('frontend.content.guru')
    @endsection
@endsection