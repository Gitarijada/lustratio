@extends('layouts.apppage')

@section('content')

    <div class="row header-container justify-content-center">
        <div class="header">
            <h2>SLUCAJEVI</h2>
        </div>
        <!-- dd(Auth::user()) }}--> 
    </div>

    @if($layout == 'index')
        <div class="container-fluid mt-4">
            <div class="row justify-content-center">
                <section class="col-md-9">
                    @include("valetudinarianslist")
                </section>
            </div>
        </div>
    @elseif($layout == 'create')
        <div class="container-fluid mt-4">
            <div class="row">
                <section class="col-md-8">
                    @include("valetudinarianslist")
                </section>
                <section class="col-md-4">

                <div class="card mb-3">
                <img src="{{ asset('images/pobuna1.jpeg') }}" class="w-1_12 mb-8 shadow-xl" alt="top_logo">
                
                <!-- validation Throw Exception. if $request.input validation - if ($errors->any())-->
                @include("layouts.validation_exception")

                <div class="card-body">
                    <!-- <h5 class="card-title">Enter the info of the new subject</h5>-->
                    
                    <form action="{{ url('/store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        @include("valetudinarian_input")
                        
                        <div class="mb-3 item" data-help-title="Upload Photo Help"
                            data-help-text="This box represents the first category. It may contain photos, files, or text data associated with section one.">
                            <label>Upload person's Photo</label>
                            <input type="file" name="image" id="image">
                        </div>

                        <input type="submit" class="btn btn-info" value="Save">
                        <!--input type="reset" class="btn btn-warning" value="Reset"-->
                        &emsp;
                        <a href="{{ url('equ') }}" class="btn btn-warning">Cancel</a>
                        <div><span class="mandatory-star-label">* denotes mandatory fields</span></div>

                    </form>
                </div>
                </div>
                </section>
            </div>
        </div>
    @elseif($layout == 'show')
        <div class="container-fluid mt-4">
            <div class="row"></div>
                <section class="col-md-7">
                    @include("valetudinarianslist")
                </section>
                <section class="col-md-5"></section>
            </div>
        </div>
    @elseif($layout == 'edit')
        <div class="container-fluid mt-4">
            <div class="row">
                <section class="col-md-8">
                    @include("valetudinarianslist")
                </section>
                <section class="col-md-4">

                <div class="card mb-3">
                <img src="{{ asset('images/pobuna1.jpeg') }}" class="w-2_12 mb-8 shadow-xl" alt="top_logo">
                
                <!-- validation Throw Exception. if $request.input validation - if ($errors->any())-->
                @include("layouts.validation_exception")
                
                <div class="card-body">
                    <h5 class="card-title">Update the info of the new subject</h5>

                    <form action="{{ url('/update/'.$item_selected->id.'?page='.$valetudinarians->currentPage()) }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label>Ime <span class="mandatory-star-label">*</span></label>
                            <input value="{{ $item_selected->first_name }}" name="first_name" type="text" class="form-control" placeholder="Enter the First Name">
                        </div>
                        <div class="mb-3">
                            <label>Prezime <span class="mandatory-star-label">*</span></label>
                            <input value="{{ $item_selected->last_name }}" name="last_name" type="text" class="form-control" placeholder="Enter the Last Name">
                        </div>
                        <div class="mb-3">
                            <label>Nadimak</label>
                            <input value="{{ $item_selected->sobriquet }}" name="sobriquet" type="text" class="form-control" placeholder="Enter the Nadimak">
                        </div>
                        <div class="mb-3-date item" data-help-title="Datum Rodjenja Help"
                            data-help-text="Datum Rodjenja nije obavezan, ali je pozeljno je imati zbog slicnih imena. U sistemu prikazujemo samo Godinu Rodjenja, a celi datum nece biti nikome vidljiv. This box represents the first category. It may contain photos, files, or text data associated with section one.">
                            <label>Datum Rodjenja</label>
                            <input value="{{ $item_selected->date_of_birth }}" name="date_of_birth" type="date" class="form-control" placeholder="Enter date of birth">
                        </div>

                        <div class="mb-3">
                            <label>Organizacija <span class="mandatory-star-label">*</span></label>
                            <select name="party_id" class="form-control">
                                @foreach($parties as $party)
                                    @if(isset($item_selected) && $party->id == $item_selected->party_id)
                                        <option value="{{$party->id}}" selected>{{$party->name}}</option>
                                    @else
                                        <option value="{{$party->id}}">{{$party->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Lokacija <span class="mandatory-star-label">*</span></label>
                            @include("location_list-template")
                        </div>

                        <div class="container-show">
                            <div class="mb-3-60prc item" data-help-title="Zanimanje Help"
                                data-help-text="Zanimanje/profesija subjekta. Pocnite da kucate zanimanje u polje, pa izaberite nesto od ponudjenog ili unesite novo zanimanje ako ni jedno od ponudjenih ne odgovara.">
                                <label>Zanimanje</label>
                                <input value="{{ $item_selected->occupation }}" name="occupation" list="occ_suggestions" type="text" class="form-control" placeholder="Enter Profesija">
                                <datalist id="occ_suggestions">
                                    @foreach ($occupations as $option)
                                        <option value="{{ $option->occupation }}">{{ $option->occupation }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="mb-3">
                                <label>Pozicija</label>
                                <input value="{{ $item_selected->position }}" name="position" type="text" class="form-control" placeholder="Enter the Owner">
                            </div>
                        </div>

                        <div class="mb-3" item" data-help-title="Description Help" 
                            data-help-text="Ako imate neke vazne podatke o samoj osobi. Podatke o dogadjaju ne unositi ovde. Polje nije obavezno.">
                            <label>Description</label>
                            <textarea name="val_description" cols="22" rows="3" class="form-control" placeholder="Do 600 karaktera max">{{ $item_selected->description }}</textarea>
                        </div>
                        
                        <div class="container-show">
                            <div class="mb-3-half">
                                <label>Email</label>
                                <input value="{{ $item_selected->email }}" name="email" type="email" class="form-control" placeholder="Enter Email">
                            </div>
                            <div class="mb-3-half">
                                <label>Telefon</label>
                                @if($item_selected->phone != null)
                                    <input value="*******{{ substr($item_selected->phone, -3) }}" name="phone" type="text" class="form-control" placeholder="Enter Phone">
                                @else
                                    <input name="phone" type="text" class="form-control" placeholder="Enter Phone">
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-3 item" data-help-title="Upload Photo Help"
                            data-help-text="This box represents the first category. It may contain photos, files, or text data associated with section one.">
                            <a href="{{ url('/upload_img/'.$item_selected->id) }}">Upload Photo for {{ $item_selected->first_name }} {{ $item_selected->last_name }}</a>
                        </div>

                        <input type="submit" class="btn btn-info" value="Update">
                        <!--input type="reset" class="btn btn-warning" value="Reset"-->
                        &emsp;
                        <a href="{{ url('equ') }}" class="btn btn-warning">Cancel</a>
                        <div><span class="mandatory-star-label">* denotes mandatory fields</span></div>
                    </form>

                </div>
                </div>

                </section>
            </div>
        </div>
    @endif       

<!-- Shared modal -->
<div id="helpModal" class="help-modal">
    <button class="help-close" id="closeHelp">&times;</button>
    <div class="help-title" id="helpTitle"></div>
    <div class="help-body" id="helpText"></div>
</div>

<!-- Info Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg">
      <div class="modal-header">  <!--div class="modal-header bg-primary text-white"-->
        <h5 class="modal-title" id="infoModalLabel">Pre Nego Pocnete</h5>
      </div>

      <div class="modal-body">
        <p>
          Ovde mozete videti listu svih subjekata sa njihovim osnovnim podatcima. Mozete dodavati nove subjekte sa opcijom iz menua "CREATE".
          Nakon kreiranja profila novog subjekta, Sistem ce vam ponuditi da unesete dogadjaj koji je povezan sa datim subjektom. Vi mozete odustati,
          tako da u sistemu ce biti unesena samo osoba bez podataka o radnjama u vezi odredjene osobe. Dogadjaj/radnju povezanu sa osobom mozete uneti i kasnije,
          opcijom "ADD".
        </p>
        <p>
          Please review the following instructions carefully before proceeding.
          This page allows you to manage data — any changes will be saved immediately.
          Make sure you have all necessary details ready.
        </p>
        <ul>
          <li>Check all form fields carefully.</li>
          <li>Data once saved cannot be reverted easily.</li>
          <li>Click “Continue” below to begin.</li>
          <li>Da bi ste uneli podatke o osobi morate biti  prijavljeni opcijom "Login".</li>
          <li>Samo korisnik koji je uneo podatke o osobi ih moze menjati. Ako ste vi osoba, pojavice vam se dugme "Edit" do podataka o toj osobi.</li>
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
@if(session('showValetudinarianInfoModal'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          var myModal = new bootstrap.Modal(document.getElementById('infoModal'));
          myModal.show();
      });
    </script>
@endif
<!-- ************************************************************* Info Modal End *** -->

@endsection
