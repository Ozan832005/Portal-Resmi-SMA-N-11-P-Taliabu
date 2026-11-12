@extends('layouts.Frontend.app')

@section('title', 'Website Resmi SMA Negeri 11 Pulau Taliabu')

@section('social_meta')
    <meta name="google-site-verification" content="iXJ3vzOf9uaebZKJSFa7gyI2bVtO1LEZnwFJ9rD-lxY" />
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta name="description" content="Website resmi SMA Negeri 11 Pulau Taliabu. Informasi profil sekolah, visi misi, kurikulum, PPDB, berita, dan kegiatan akademik terkini." />
    <meta property="og:title" content="SMA Negeri 11 Pulau Taliabu" />
    <meta property="og:description" content="Website resmi SMA Negeri 11 Pulau Taliabu. Informasi profil sekolah, kurikulum, PPDB, dan berita terkini." />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:type" content="website" />
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "SMA Negeri 11 Pulau Taliabu",
      "alternateName": "SMAN 11 Pulau Taliabu",
      "url": "{{ url('/') }}"
    }
    </script>
@endsection

@section('facts')
    @include('frontend.content.stats')
@endsection

@section('slider')
    @include('frontend.content.slider')
@endsection

@section('about')
    @include('frontend.content.about')
@endsection

@section('video')
    @include('frontend.content.vidio')
@endsection

@section('guru')
    @include('frontend.content.guru')
@endsection

@section('beritaEvent')
    @include('frontend.content.beritaEvent')
@endsection
