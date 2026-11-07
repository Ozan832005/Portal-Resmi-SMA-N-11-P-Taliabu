@extends('layouts.Frontend.app')

@section('title', __('Beranda'))

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
