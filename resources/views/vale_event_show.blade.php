@extends('layouts.apppage')

@section('content')      
    <div class="row header-container justify-content-center">
        <div class="header">
            <h2>EVENT'S EQUIPMENT</h2>
        </div>
    </div>
    @if($layout == 'index')
        <div class="container-fluid mt-4">
            <div class="row justify-content-center">
                <section class="col-md-9">
                    @include("equlist")
                </section>
            </div>
        </div>
    @elseif($layout == 'create')
        <div class="container-fluid mt-4">
            @include("equipmentslist_check")
        </div>
    @elseif($layout == 'add')
        <div class="container-fluid mt-4">
            @include("equipmentslist_check")
        </div>
    @elseif($layout == 'show')
        
    @elseif($layout == 'edit')
        
    @endif       
@endsection   

