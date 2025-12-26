@extends('layouts.apppage')

@section('content')      
    <div class="row header-container justify-content-center">
        <div class="header">
            <h2>IMAGE</h2>
        </div>
        <!-- dd(Auth::user()) }}--> 
    </div>
   
    @if($layout == 'image_upload')
        <div class="container-fluid mt-4">
            <div class="row justify-content-center">
                <section class="col-md-9">
                <div class="card mb-3">
                    <div class="card-header">Upload Images / Photos</div>
                <div class="card-body">
                    <!-- validation Throw Exception. if $request.input validation - if ($errors->any())-->
                    @include("layouts.validation_exception")

                    <!--form action="{-{ route('upload.store') }}" method="POST" enctype="multipart/form-data"-->
                    @if($image_type == 'valetudinarian')
                    <form action="{{ url('store_img') }}" method="POST" enctype="multipart/form-data">
                    @elseif($image_type == 'event')
                    <form action="{{ url('store_event_img') }}" method="POST" enctype="multipart/form-data">
                    @elseif($image_type == 'guess')
                    <form action="{{ url('store_guess_img') }}" method="POST" enctype="multipart/form-data">
                    @endif
                        @csrf
                        <div class="col-md-6">
                            <input type="file" name="image" id="image">
                        </div>
                        <div class="col-md-6">
                            @if($image_type != 'guess')
                            <input id="id" type="hidden" name="parent_id" value={{ $id }}>
                            @else
                            <input id="id" type="hidden" name="parent_id" value="">
                                <br>
                                <label>Description</label>
                                <textarea name="description" cols="20" rows="4" class="form-control" placeholder="Enter Description"></textarea>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <br>
                            <button type="submit">Upload</button>
                        </div>
                    </form>

                </div>
                </div>
                </section>
            </div>
        </div>
    @endif    

@endsection   

