@extends('layouts.Frontend.app')

@section('title', __('Katalog Perpustakaan | SMA 11 Pulau Taliabu'))

@section('content')
    @section('about')
        <main class="km-library-shell">
            <div class="container">
                <header class="km-library-hero">
                    <span class="km-eyebrow km-eyebrow--dark"><i class="fa fa-book" aria-hidden="true"></i> {{ __('Perpustakaan sekolah') }}</span>
                    <h1>{{ __('Temukan buku untuk ide dan masa depanmu') }}</h1>
                    <p>{{ __('Jelajahi koleksi buku SMA Negeri 11 Pulau Taliabu berdasarkan kategori.') }}</p>
                    <span class="km-library-total"><i class="fa fa-bookmark" aria-hidden="true"></i> {{ __(':count judul tersedia di katalog', ['count' => $booksByCategory->flatten(1)->count()]) }}</span>
                </header>

                @if ($booksByCategory->isEmpty())
                    <div class="km-empty">
                        <i class="fa fa-book" aria-hidden="true"></i>
                        <strong>{{ __('Koleksi buku belum tersedia') }}</strong>
                        <span>{{ __('Buku yang tercatat di perpustakaan akan ditampilkan di halaman ini.') }}</span>
                    </div>
                @else
                    <div class="km-library-categories">
                        @foreach ($booksByCategory as $categoryName => $books)
                            <section class="km-library-category" aria-labelledby="category-{{ \Illuminate\Support\Str::slug($categoryName) }}">
                                <div class="km-library-category__head">
                                    <div>
                                        <span class="km-library-category__eyebrow">{{ __('Koleksi berdasarkan jenis') }}</span>
                                        <h2 id="category-{{ \Illuminate\Support\Str::slug($categoryName) }}">{{ $categoryName }}</h2>
                                    </div>
                                    <span class="km-library-category__count">{{ __(':count buku', ['count' => $books->count()]) }}</span>
                                </div>

                                <div class="km-library-grid">
                                    @foreach ($books as $book)
                                        <article class="km-library-book">
                                            <div class="km-library-book__cover">
                                                @if ($book->thumbnail)
                                                    <img src="{{ route('katalog.perpustakaan.sampul', $book) }}" alt="Sampul {{ $book->name }}" loading="lazy">
                                                @else
                                                    <i class="fa fa-book" aria-hidden="true"></i>
                                                @endif
                                            </div>
                                            <div class="km-library-book__body">
                                                <h3>{{ $book->name }}</h3>
                                                <p class="km-library-book__author">
                                                    <i class="fa fa-user" aria-hidden="true"></i>
                                                    {{ $book->author?->name ?? __('Penulis tidak dicantumkan') }}
                                                </p>
                                                <p class="km-library-book__description">{{ \Illuminate\Support\Str::limit($book->description, 130) }}</p>
                                                <div class="km-library-book__meta">
                                                    <span><i class="fa fa-calendar" aria-hidden="true"></i> {{ $book->publication_year }}</span>
                                                    <span><i class="fa fa-cubes" aria-hidden="true"></i> {{ __(':count tersedia', ['count' => $book->stock]) }}</span>
                                                </div>
                                                <div class="km-library-book__footer">
                                                    <span class="km-library-book__publisher">{{ $book->publisher?->name ?? __('Penerbit tidak dicantumkan') }}</span>
                                                    @if ($book->stock > 0)
                                                        <a class="km-library-checkout" href="{{ route('login') }}">
                                                            {{ __('Pinjam buku') }} <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                                        </a>
                                                    @else
                                                        <span class="km-library-checkout km-library-checkout--disabled" aria-disabled="true">{{ __('Stok habis') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>
                @endif
            </div>
        </main>
    @endsection
@endsection
