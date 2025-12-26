@extends('layouts.apppage')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="col-md-8">
            <form method="POST" action="{{ route('register') }}">
                @csrf
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    
                    <!--form method="POST" action="{-{ route('register') }}">
                        @-csrf-->
                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row item" data-help-title="Ime Help"
                            data-help-text="eMail je obavezan da bi se registrovali.Za uspesnu registraciju je potrebno da potvrdite mail poruku koju ce te dobiti na vas mail">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    <!--/form-->
                </div>
            </div>
            <div class="col-md-2"><label></label></div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }} - Nonessential</div>

                    <div class="card-body">

                        <div class="form-group row item" data-help-title="Ime Help"
                            data-help-text="Nije obavezao za registraciju. Ni u kom slucaju se nece koristiti u bilo koje druge svrhe">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Ime</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control" name="first_name">
                            </div>
                        </div>
                        <div class="form-group row item" data-help-title="Prezime Help"
                            data-help-text="Nije obavezno za registraciju. Ni u kom slucaju se nece koristiti u bilo koje druge svrhe">
                            <label for="last_name" class="col-md-4 col-form-label text-md-right">Prezime</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control" name="last_name">
                            </div>
                        </div>
                        <div class="form-group row item" data-help-title="Telefon Help"
                            data-help-text="Unos telefona nam pomaze da bi potencijalno mogli overiti tacnost unesenih podataka kao i identifikacija raznih 'botova'. Nije obavezan unos da bi se registrovali">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">Telefon</label>

                            <div class="col-md-6">
                                <input id="phone" type="phone" class="form-control" name="phone">

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3">
                                <label>Lokacija</label>
                                @include("location_list-template")
                            </div>
                        </div>

                    </div>       
                </div>
            </div>

            </form>
        </div>

    </div>
</div>

<!-- Shared modal -->
<div id="helpModal" class="help-modal">
    <button class="help-close" id="closeHelp">&times;</button>
    <div class="help-title" id="helpTitle"></div>
    <div class="help-body" id="helpText"></div>
</div>

<!--div class="modal fade" id="viewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center position-relative"-->
<!-- Info Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content shadow-lg">
      <div class="modal-header">  <!--div class="modal-header bg-primary text-white"-->
        <h5 class="modal-title" id="infoModalLabel">Registracija</h5>
      </div>
      <div class="modal-body">
        <p>
          Ovde kreirate svoj profil. Obavezni su osnovni podatci (ime, eMail adresa i lozinka). Po unosu ovih podataka ce te na datu email adresu dobiti 
          poruku gde mozete potvrditi i aktivirati profil. Bez aktivacije i prijavljivanja necete moci da koristite vecinu opcija (unos, menjanje...) sistema, osim pregleda osoba i dogadjaja.
        </p>
        <p>
          U drugom delu postoji mogucnost unosa dodatnih podataka. <strong>Njihov unos nije obavezan da bi se otvorio profil.</strong>
          Ipak, njihov unos nam pomaze da bi potencijalno mogli overiti tacnost unesenih podataka kao i olaksavanje sistemu da sto vise onemoguci i identifikuje razne "botove". 
        </p>
        <ul>
          <li>Da bi ste imali pristup vecini funkcija sistema morate biti  prijavljeni opcijom "Login".</li>     
          <li>Samo korisnik koji je uneo podatke, iste moze menjati.</li>
          <li>Uneti podatci u registraciji se nece nikde drugo koristiti osim u ovom sistemu, niti davati trecim stranama ni u kakvom slucaju</li>
        </ul>
      </div>
      <div class="modal-footer">
        <!--button type="button" class="btn btn-primary" data-bs-dismiss="modal">Continue</button>
        <button type="button" class="btn btn-sm btn-outline-danger toolbar-btn" data-bs-dismiss="modal">Continue</button-->
        <button type="button" class="btn btn-sm btn-outline-primary toolbar-btn" data-bs-dismiss="modal">Continue</button>
        
      </div>
    </div>
  </div>
</div>

<!-- ************************************************************* Info Modal ****** -->
<!--@-if($showModal)    if(session('showInfo'))-->
<!--@-if(session('showEventInfoModal'))-->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('infoModal'));
        myModal.show();
    });
</script>
<!--@-endif-->
<!-- ************************************************************* Info Modal End *** -->

@endsection
