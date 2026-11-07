@extends('layouts.Frontend.app')

@section('title')
    {{ __('Profile Sekolah') }}
@endsection

@section('content')
    @section('about')
        <div class="km-profile-shell">
            <div class="container">
                @if ($profile)
                    <div class="km-page-head">
                        <span class="km-eyebrow"><i class="fa fa-university" aria-hidden="true"></i> {{ __('Profil sekolah') }}</span>
                        <h1>{{ $profile->title ?: __('Profil Sekolah') }}</h1>
                    </div>
                    <div class="km-profile-layout">
                        <div class="km-profile-content">
                            {!! $profile->content !!}
                        </div>
                        <div class="km-profile-visual">
                            <img src="{{ asset('storage/images/profileSekolah/' . $profile->image) }}" alt="{{ __('Profil sekolah') }} SMA Negeri 11 Pulau Taliabu">
                        </div>
                    </div>
                @else
                    <div class="km-empty">
                        <i class="fa fa-building" aria-hidden="true"></i>
                        <strong>{{ __('Profil sekolah belum tersedia') }}</strong>
                        <span>{{ __('Informasi profil sekolah akan muncul di sini.') }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endsection

    @section('guru')
        @include('frontend.content.guru')
    @endsection
@endsection