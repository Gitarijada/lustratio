@extends('layouts.apppage')

@section('content')   

    <div class="row header-container justify-content-center">
        <div class="header">
            <h2>EVENTS</h2>
        </div>
        <!-- dd(Auth::user()) }}--> 
    </div>

    @if($layout == 'index')
        <div class="container-fluid mt-4">
            <div class="row justify-content-center">
                <section class="col-md-9">
                    @include("eventslist")
                </section>
            </div>
        </div>
    @elseif($layout == 'create')
        <div class="container-fluid mt-4">
            <div class="row">
                <section class="col-md-8">
                    @if($param == 0) 
                        @include("eventslist")
                    @elseif($param == 1)
                        <!--From ValetrudianController->Save - EventController->create_event_valeid($id)-'param' => 1 -->
                        @include("valelist_check")
                    @endif
                </section>
                <section class="col-md-4">

                <div class="card mb-3">
                <img src="{{ asset('images/pobuna.jpeg') }}" class="w-1_12 mb-8 shadow-xl" alt="top_logo">

                <!-- validation Throw Exception. if $request.input validation - if ($errors->any())-->
                @include("layouts.validation_exception")
                
                <div class="card-body">
                    <h5 class="card-title">Enter the info of the new event</h5>
                    
                    <form action="{{ url('/store-event') }}" method="post" enctype="multipart/form-data">   <!--if more type of data like files->"multipart/form-data"-->
                        @csrf
                        
                        @if($param == 1)
                            @foreach($valetudinarians as $item)
                                <input id="valetudinarian_id" hidden name="valetudinarian_id" value={{ $item->id }}>
                            @endforeach
                        @endif

                        @include("event_input-main")
                        @include("event_input-rest")
                        
                        <input type="submit" class="btn btn-info" value="Save">
                        <!--input type="reset" class="btn btn-warning" value="Reset"-->
                        &emsp;
                        <a href="{{ url('list-event') }}" class="btn btn-warning">Cancel</a>
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
                    @include("eventslist")
                </section>
                <section class="col-md-5"></section>
            </div>
        </div>
    @elseif($layout == 'edit')
        <div class="container-fluid mt-4">
            <div class="row">
                <section class="col-md-8">
                    @include("eventslist")
                </section>
                <section class="col-md-4">

                <div class="card mb-3">
                <img src="{{ asset('images/pobuna.jpeg') }}" class="w-2_12 mb-8 shadow-xl" alt="top_logo">

                <!-- validation Throw Exception. if $request.input validation - if ($errors->any())-->
                @include("layouts.validation_exception")
                
               <div class="card-body">
                    <h5 class="card-title">Update the info of the new subject</h5>

                    <form action="{{ url('/update-event/'.$item_selected->id.'?page='.$events->currentPage()) }}" method="post">
                        @csrf
                        @include("event_input-main")
                        @include("event_input-rest")

                        <input type="submit" class="btn btn-info" value="Update">
                        <!--input type="reset" class="btn btn-warning" value="Reset"-->
                        &emsp;
                        <a href="{{ url('list-event') }}" class="btn btn-warning">Cancel</a>
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

<!--div class="modal fade" id="viewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center position-relative"-->
<!-- Info Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg">
      <div class="modal-header">  <!--div class="modal-header bg-primary text-white"-->
        <h5 class="modal-title" id="infoModalLabel">Pre Nego Pocnete</h5>
      </div>
      <div class="modal-body">
        <p>
          Ovde mozete videti listu svih dogadjaja/radnji vezanih za subjekat sa osnovnim informacijama. Mozete dodavati nove dogadjaje sa opcijom iz menua "CREATE".
          Nakon kreiranja novog dogadjaja, isti mozete povezati sa subjektom opcijom "ADD". Jedan subjekt moze imati vise dogadjaja/radnji, kao sto i dogadjaj ili 
          neka radnja mozimati vise osoba povezanih sa njom.
          Nakon kreiranja profila novog dogadjaja, Sistem ce vam ponuditi da unesete ... Zato uvek prvo unosite osobu koja je izvrasila, ili je povezana sa tim dogadjajem. 
          Dogadjaj/radnju unesenu u sistem uvek mozete kasnije menjati, a i dodavati jednu ili vise osoba povezanih sa tom radnjom, opcijom "ADD".
        </p>
        <p>
          Please review the following instructions carefully before proceeding.
          This page allows you to manage data — any changes will be saved immediately.
          Make sure you have all necessary details ready.
        </p>
        <ul>
          <li>Check all form fields carefully.</li>
          <li>Da bi ste uneli podatke o dogadjaju morate biti  prijavljeni opcijom "Login".</li>
          <li>Samo korisnik koji je uneo podatke o dogadjaju ih moze menjati. Ako ste vi osoba, pojavice vam se dugme "Edit" do podataka o toj osobi.</li>
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
@if(session('showEventInfoModal'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          var myModal = new bootstrap.Modal(document.getElementById('infoModal'));
          myModal.show();
      });
    </script>
@endif
<!-- ************************************************************* Info Modal End *** -->

@endsection   

