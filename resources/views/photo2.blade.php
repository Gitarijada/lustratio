@extends('layouts.apppage')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            text-align: center;
            padding: 40px;
        }
        img {
            max-width: 90%;
            max-height: 80vh;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .nav-arrow {
            position: fixed;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3rem;
            color: #333;
            text-decoration: none;
            padding: 10px;
            background: rgba(255,255,255,0.8);
            border-radius: 50%;
            transition: 0.2s;
        }
        .nav-arrow:hover {
            background: rgba(255,255,255,1);
            transform: translateY(-50%) scale(1.1);
        }
        .left-arrow { left: 40px; }
        .right-arrow { right: 40px; }
    </style>


    @if($photo)
        @if($prev !== null)
            <a href="{{ url('fame/' . $prev) }}" class="nav-arrow left-arrow">&#8592;</a>
        @endif

        <img src="{{ asset($photo) }}" alt="Photo">

        @if($next !== null)
            <a href="{{ url('fame/' . $next) }}" class="nav-arrow right-arrow">&#8594;</a>
        @endif

        <!--p class="mt-3 text-muted">{-{ basename($photo) }}</p-->
    @else
        <p>No photos found in folder.</p>
    @endif

@endsection