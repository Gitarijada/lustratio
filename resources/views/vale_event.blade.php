@extends('layouts.apppage')

@section('content')      
<div class="row header-container justify-content-center">
    <div class="header">
        @if($layout == 'show')
            <h2>{{ $event->event_name }}</h2>
        @else
            <h2>ADDING VALETUDINARIAN TO EVENT</h2>
        @endif
    </div>
</div>

<div class="container-fluid mt-4">
    <div class="col">
    <!--form action="{-{ url('/store-valeevent') }}" method="post"-->
         @csrf             
        <div class="row">
            <section class="col-md-8">
<!--@-if(($layout == 'add'--//index() of ValeEventController) || ($layout == 'create') || ($layout == 'create_passID') || ($layout == 'show'))-->

                @include("valetudinarianslist_check")

                @if($layout == 'show')
                    <div id="photo-section" class="card mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            @if($images != null) 
                                @foreach($images as $item)
                                    <div class="mb-3">
                                        <!--{-{ print_r(url('')) }}-->
                                        <!--img src="{-{ asset('images/tmp/' . $item->image_name) }}" alt="{-{ $item->image_name }}"-->
                                        <img src="{{ asset('storage/event_images/' . $item->image_name) }}" class="w-100 mb-8 shadow-xl" alt="{{ $item->image_name }}">
                                        <!--img src="{-{ url('/') . 'storage/app/' . $item->image_name }}" alt="{-{ $item->image_name }}"-->
                                        <!--img src="{-{ env('APP_URL').'/storage'. $item->image_name }}" alt="{-{ $item->image_name }}"-->
                                    </div>        
                                @endforeach
                            @endif
                        </div>
                        <div class="mb-3">
                            <label>Created At</label>
                            <input value="{{ $event->created_at }}" readonly name="created_at" type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Updated At</label>
                            <input value="{{ $event->updated_at }}" readonly name="updated_at" type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            @if (auth()->user() && isset(Auth::user()->id))
                                @if ((Auth::user()->id == $event->owner_id) || Auth::user()->id == 1)
                                    <a href="{{ url('/edit-event/'.$event->id) }}" class="btn btn-sm btn-info">Edit</a>
                                @else
                                    <p>"Only the person who made the following entry is allowed to Edit or Upload Photos, after Login"</p> 
                                    <p>"PERMISSION TO EDIT" only to the original creator of record.</p>
                                @endif

                                @if (Auth::user()->id == 1)
                                    <a href="{{ url('/destroy-event/'.$event->id) }}" class="btn btn-sm btn-danger">Delete</a>
                                @endif
                            @endif
                        </div>
                    </div>            
                    </div>
                @endif
            </section>

            <section class="col-md-4">
                <div class="card mb-3">
                <img src="{{ asset('images/pobuna.jpeg') }}" class="w-1_12 mb-8 shadow-xl" alt="top_logo">
                
                <!-- validation Throw Exception. if $request.input validation - if ($errors->any())-->
                @include("layouts.validation_exception")
                
                    <!-- right side Input for Events-->
                    <div class="card-body">

                    @if(($layout == 'create') OR ($layout == 'add'))

                        @include("event_input-main")
                        <div id="data-event-rest-append"></div> <!-- that we can show rest event data once user choose Event -->
                        @if($layout == 'create')@include("event_input-rest")@endif

                    @elseif(($layout == 'create_passID') OR ($layout == 'show'))

                        @if($category != NULL)
                            <div class="mb-3">
                            <label>KATEGORIJA</label>
                                <input disabled value="{{ $category->category_name }}" name="category_name">
                            </div>
                        @endif

                        <div class="dropdown-divider"></div>
                        <div class="mb-3">
                            <label>Event Name</label>
                            <input id="ev_id" type="hidden" name="event_id" value={{ $event->id }}>
                            <div><input disabled value="{{ $event->event_name }}" class="form-control"></div>
                        </div>

                        @if(isset($location))
                            <div class="mb-3">
                            <label>LOKACIJA</label>
                                <input disabled value="{{ $location->zip }} {{ $location->name }}" name="location_name">
                            </div>
                        @endif

                        @if(isset($event->event_date))
                            <div class="mb-3-date">
                                    <label>Date</label>
                                    <input disabled value="{{ Carbon\Carbon::parse($event->event_date)->format('M Y') }}">
                            </div>
                        @endif
                        
                    @endif
                        
                    @if($layout == 'show')
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea disabled name="event_description" cols="40" rows="12" class="form-control">{{ $event->description }}</textarea>
                        </div>
                    @else
                        <!--input type="submit" class="btn btn-info" value="Save">
                        <!X--input type="reset" class="btn btn-warning" value="Reset"--X>
                        &emsp;
                        <a href="{-{ url('list-event') }}" class="btn btn-warning">Cancel</a-->
                        <a id="confirmBtn" class="btn btn-sm btn-outline-primary toolbar-btn">Confirm & Continue</a>
                    @endif
                    
                    </div>
                </div>
            </section>    
        </div>
    <!--/form-->
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

<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content shadow-lg">
            <form action="{{ url('/store-valeevent') }}" method="post">
                @csrf 
            <div style="padding-top: 15px;"></div>
            <section class="col-md-12">
                <div id="data-confirm-append">
                <div id="data-confirm">

                </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-info" value="Save">
                    &emsp;
                    <!-- With page refresh. To reset selected options. data-bs-dismiss="modal" cancel without page refresh-->
                    <a class="btn btn-warning" data-bs-dismiss="modal" onclick="window.location.reload();">Cancel</a>
                    <!--button type="button" class="btn btn-primary" data-bs-dismiss="modal">Continue</button>
                    <button type="button" class="btn btn-sm btn-outline-danger toolbar-btn" data-bs-dismiss="modal">Continue</button-->
                    <!--button type="button" class="btn btn-sm btn-outline-primary toolbar-btn" data-bs-dismiss="modal">Continue</button-->
                    
                </div>
            </section>
            </form>
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
<script src="{{ asset('js/selection-event.js') }}"></script>
    
<script type="text/javascript">     
    $('#data-event-main-append').on('change', '#category_id', function () {
        const categorySelect = document.getElementById('category_id').value;
        const regionSelect = document.getElementById('region_id').value;
        var locationID = document.getElementById('location_id').value;
        const local_Select = document.getElementById('local_id').value;
        const urlRoute = "{{ url('/') }}" + "/valeevent-ajax";

        if (local_Select.length !== 0) locationID = local_Select;
        if (locationID.length === 0) toastr.info("LOKACIJA nije izabrana za selekciju DOGADJAJA.", 'Event'); 
//alert('MCat '+categorySelect+' reg-> '+regionSelect+' L-> '+locationID+' local-> '+local_Select);
        fetch_select_events(urlRoute, categorySelect, regionSelect, locationID, 'KATEGORIJA izabrana za Event selekciju.')
    });

    $('#data-event-main-append').on('change', '#region_id, #location_id', function() {     //not in use #location_id is not on the page. Maybe to expand later
        const categorySelect = document.getElementById('category_id').value;
        const regionSelect = document.getElementById('region_id').value;
        var locationID = document.getElementById('location_id').value;
        const local_Select = document.getElementById('local_id').value;
        const urlRoute = "{{ url('/') }}" + "/valeevent-ajax";

        var message = 'LOKACIJA izabrana za selekciju DOGADJAJA.';
        if (local_Select.length !== 0) {
            locationID = local_Select;
        } else if (regionSelect.length !== 0) message = 'REGION izabran. Izaberi GRAD za selekciju DOGADJAJA.';
        if (categorySelect.length === 0) toastr.info("KATEGORIJA nije izabrana za selekciju DOGADJAJA.", 'Event'); 

		fetch_select_events(urlRoute, categorySelect, regionSelect, locationID, message)
    });
    
    $('#data-event-main-append').on('change', '#ev_id', function () {
        const eventID = $(this).val();
        const partyID = $('#party_filter').val();
        const locationID = $('#location_filter').val();
        const regionSelected = $('#region_filter').val();
        
        if (eventID == null) { 
            toastr.success('You must have an event', 'Event');
            return; 
        }

        const filterRoute = "{{ route('vale.event-input') }}";
        get_data_event(filterRoute, eventID)    //get_data_event(filterRoute, eventID, type = 'data-event-rest')

        const filterRoute_list = "{{ route('vale.filter') }}";
        get_data_output(filterRoute_list, eventID, null, partyID, regionSelected, locationID, null)
    });
    
//********************************************************************** Confirmation *****
    const confirmBtn = document.getElementById('confirmBtn');
    confirmBtn.addEventListener('click', () => {
        //if (!confirm(`"Save". You are going to add the following persons to event.`)) return;
        const checkedCheckboxes = document.querySelectorAll('input[name="vale_selected[]"]:checked');
        const vale_selected = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);
        if (vale_selected.length === 0) {
            alert("You have not specified which persons you are referring to. To identify the persons connected to the event, please check/choose the persons !!!");
            return
        } else {
            const eventID = $('#ev_id').val();
                            //alert(vale_selected.toString());  
            get_data_confirmation(eventID, vale_selected)
        }
    });

    function get_data_confirmation(eventID = null, valeSelected = null) {
        //const SITEURL = "{{ url('/') }}";
        $.ajax({
            type:'POST',
            //url: SITEURL + '/output-ajax',
            url:"{{ route('vale.confirm') }}",
            data:{
                _token: "{{ csrf_token() }}",
                event_ID:eventID,
                vale_selected:valeSelected,
                type:'data-confirmation'
            },
            success:function(data){
                var message = valeSelected.toString();
                if(data){
                    toastr.info(message, 'Criterias...');
              
                    $('#data-confirm').remove();
                    //$('#pagination-links').remove();
                    $('#data-confirm-append').append(data.html);
                                                                        /*const endTime = Date.now();
                                                                        const elapsedTime = endTime - startTime;
                                                                        alert('JSON ' + partyID + ' ' + elapsedTime);*/
                    var confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
                    confirmModal.show();
                }else{
                    toastr.info('Oops Something went wrong" error on processing no matter ...', 'Loading');
                }
            },
                error: function(xhr, status, error) {
                console.error('Error loading data:', error);
            }
        });
    }
    //**********************************************Confirmation End***
</script>

@endsection   

