@extends('layouts.apppage')

@section('content')      
    <div class="row header-container justify-content-center">
        <div id="new_val_event_hx" class="header">
            <h2>ADD NEW CASE AND EVENT</h2>
        </div>
        <!--{-{ dd(Auth::user()) }}--> 
    </div>

    @if($layout == 'make_famous' || $layout == 'create_vale_event')
        <div class="container-fluid mt-4">
            <form action="{{ url('/store_all') }}" onsubmit="return confirmEventSubmit()" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <section class="col-md-4">

                <div class="card mb-3">
                <!--img src="{-{ asset('images/pobuna1.jpeg') }}" class="w-1_12 mb-8 shadow-xl" alt="top_logo"-->
                    <div class="card-body">
                        
                        @include("valetudinarian_input")
                        
                    </div>
                </div>
                </section>

                <section class="col-md-4">
                    
                <!-- validation Throw Exception. if $request.input validation - if ($errors->any())-->
                @include("layouts.validation_exception")

                @if($layout == 'make_famous')
                    <div id="photo-section" class="card mb-3">
                        <div class="card-body">
                            <div class="mb-3">
                                @if($image_guess != null) 
                                    <div class="mb-3">
                                        <img src="{{ asset('storage/guess_images/' . $image_guess->image_name) }}" class="w-50 mb-8 shadow-xl" alt="{{ $image_guess->id }}">
                                    </div>        
                                @endif
                            </div>
                        </div>            
                    </div>
                @endif
                <div class="mb-3 item" data-help-title="Upload Photo Help"
                            data-help-text="This box represents the first category. It may contain photos, files, or text data associated with section one.">
                    <label>Upload (more) person's Photo</label><br>
                    <input type="file" name="image" id="image">
                </div>

                <br>
                <input type="submit" class="btn btn-info" value="Save">&emsp;
                <a href="{{ url('equ') }}" class="btn btn-warning">Cancel</a>
                <div><span class="mandatory-star-label">* denotes mandatory fields</span></div>

                @if($layout == 'make_famous')
                    <input id="id_guess" type="hidden" name="id_guess" value={{ $image_guess->id }}>
                @endif

                </section>

                <section class="col-md-4">
                    @include("event_input-main")
                    @include("event_input-rest")
                </section>
                
            </div>
            </form>
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

<script>
//*********************************************** form submit Event check ******
//check event_name and description field, and if empty, ask if user wanna continue without Event data (with only val data)
    function confirmEventSubmit() {
        const inputEvent_name = document.getElementById('event_name').value;
        const inputDescription = document.getElementById('description').value;
            
        if ((inputEvent_name === '' || inputEvent_name.trim() === '') && 
            (inputDescription === '' || inputDescription.trim() === '')) {
            // The confirm() function returns true if the user clicks "OK", false otherwise.
            return confirm("There is no any mandatory data (event_name and description field) for the 'Event', for the given person. Are you sure you don't want add Events data this time too?");
            //if (!confirm(`Ya gonna make entry for ...`)) return;
        }
    }
//**********************************************  form submit Event check End ***
</script>
@endsection  

@push('scripts')
    <script src="{{ asset('js/locationSelector2.js') }}"></script>  <!-- locationSelector2.js, second one, beware, there is locationSelector.js-not used so fare -->
    <script src="{{ asset('js/selection-event.js') }}"></script>
    <script src="{{ asset('js/existingEventOnChangeSelector.js') }}"></script>
    <script src="{{ asset('js/choose-newSlector.js') }}"></script>
@endpush

@stack('rest-scripts') <!-- progress-bar This is where your JS will be injected -->
