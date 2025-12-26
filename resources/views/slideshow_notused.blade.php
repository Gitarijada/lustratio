@extends('layouts.apppage')

@section('content')
    <!--link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />  <!--asset('css/bootstrap.min2.css') = public + css/bootstrap.min2.css-->
    <!--link href="{ { asset('css/lightbox.min.css') }}" rel="stylesheet" /-->
    <!--link href="{ { asset('css/lightbox.min.css') }}" rel="stylesheet" /-->
    <!--  {-!! HTML::style('css/bootstrap.min2.css') !!}  //this work as well-->
    <style>
        body { background: #f8f9fa; }
        .gallery { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
            gap: 20px; 
            padding: 40px; 
        }
        .gallery img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.15);
            transition: transform .2s;
        }
        .gallery img:hover { transform: scale(1.05); }
    </style>


<div class="container">
    <div class="row header-container justify-content-center">
        <div class="header">
            <h2>Ucinimo ih Poznatim Galerija</h2>
        </div>
        <!-- dd(Auth::user()) }}--> 
    </div>

    <div class="gallery">
        @foreach($photos as $photo)
            <a href="{{ asset($photo) }}" data-lightbox="gallery" data-title="{{ basename($photo) }}">
                <img src="{{ asset($photo) }}" alt="Photo">
            </a>
        @endforeach
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>
<script src="{{ asset('js/lightbox.min.css') }}"></script>
@endsection
