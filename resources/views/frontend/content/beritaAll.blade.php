@extends('layouts.Frontend.app')

@section('title')
    {{ __('Berita') }}
@endsection

@section('content')
    @section('about')
        @if ($featureItems->isNotEmpty())
            @include('frontend.content.partials.news-feature', ['items' => $featureItems])
        @endif

        <h1 class="km-news-sr">{{ __('Berita sekolah') }}</h1>

        <div class="news-page-area">
            <div class="container">
                <nav class="km-news-crumbs" aria-label="{{ __('Breadcrumb') }}">
                    <a href="{{ url('/') }}"><i class="fa fa-home" aria-hidden="true"></i> {{ __('Beranda') }}</a>
                    <span class="km-news-crumbs__sep" aria-hidden="true">/</span>
                    <a href="{{ route('berita') }}">{{ __('Berita') }}</a>
                    <span class="km-news-crumbs__sep" aria-hidden="true">/</span>
                    <span class="km-news-crumbs__current">{{ $activeKategori ? $activeKategori->nama : __('Semua Berita') }}</span>
                </nav>

                <div class="km-news-tools">
                    <form class="km-news-search" method="GET" action="{{ route('berita') }}" role="search">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <input type="search"
                               name="search"
                               value="{{ $search }}"
                               placeholder="{{ __('Cari berita di sini...') }}"
                               aria-label="{{ __('Cari berita') }}">
                        @if ($activeKategoriId > 0)
                            <input type="hidden" name="kategori" value="{{ $activeKategoriId }}">
                        @endif
                    </form>

                    <div class="km-news-filters" role="group" aria-label="{{ __('Filter kategori berita') }}">
                        @php
                            $searchParam = $search !== '' ? ['search' => $search] : [];
                        @endphp

                        <a href="{{ route('berita', $searchParam) }}"
                           class="km-news-tool {{ $activeKategoriId === 0 ? 'is-active' : '' }}"
                           @if ($activeKategoriId === 0) aria-current="true" @endif>{{ __('Semua Berita') }}</a>

                        @foreach ($kategori as $kategoris)
                            <a href="{{ route('berita', array_merge($searchParam, ['kategori' => $kategoris->id])) }}"
                               class="km-news-tool {{ $activeKategoriId === (int) $kategoris->id ? 'is-active' : '' }}"
                               @if ($activeKategoriId === (int) $kategoris->id) aria-current="true" @endif>{{ $kategoris->nama }}</a>
                        @endforeach
                    </div>
                </div>

                @if ($search !== '')
                    <p class="km-news-search-empty">
                        {{ __('Hasil pencarian untuk') }} <strong>{{ $search }}</strong>
                        @if ($activeKategori)
                            {{ __('di kategori') }} <strong>{{ $activeKategori->nama }}</strong>
                        @endif
                    </p>
                @endif

                @if ($berita->isEmpty())
                    <div class="km-news-empty">
                        <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                        <strong>{{ $search !== '' ? __('Berita tidak ditemukan') : __('Belum ada berita') }}</strong>
                        <span>{{ $search !== '' ? __('Coba gunakan kata kunci pencarian yang lain.') : __('Berita yang dipublikasikan akan muncul di sini.') }}</span>
                    </div>
                @else
                    <div class="km-news-grid">
                        @foreach ($berita as $beritas)
                            @include('frontend.content.partials.news-card', [
                                'item' => $beritas,
                                'variant' => 'grid',
                            ])
                        @endforeach
                    </div>

                    {{ $berita->links('frontend.content.paginate') }}
                @endif
            </div>

            @include('frontend.content.partials.news-modal')
        </div>
    @endsection
@endsection
