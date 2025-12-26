@extends('layouts.apppage')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('You do not have permission to access this item.') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('You do not have required permissions to access this item.') }}
                        </div>
                    @endif

                    {{ __('There is set up for factor authentication and you have not authenticated in such ways...') }}
                    <div></div> <!--make new line-->
                    {{ __('You still can do surfing through data exploring people and events') }}

                    <div class="dropdown-divider"></div>
                    <div class="dropdown-divider"></div>
                    <div class="mb-3">
                        <!--a class="nav-link" href="{-{ route('register') }}">{-{ __('Register') }}</a-->
                        <a class="btn btn-info" href="{{ url('/') }}">{{ __('Go to Main Page') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
