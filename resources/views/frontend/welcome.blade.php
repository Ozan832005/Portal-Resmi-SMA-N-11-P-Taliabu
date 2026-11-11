@extends('layouts.Frontend.app')

@section('title', __('Beranda'))

@section('social_meta')
    <meta name="google-site-verification" content="iXJ3vzOf9uaebZKJSFa7gyI2bVtO1LEZnwFJ9rD-lxY" />
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
