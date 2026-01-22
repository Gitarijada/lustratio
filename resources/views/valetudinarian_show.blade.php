@extends('layouts.apppage')

@section('content')  
    @if((int)floor($item_selected->status / 10) == 3)    <!-- (int)substr($item_selected->status, 0, 1) == 3 -->
    <div class="row header-container justify-content-center subject-line_A">
    @elseif((int)floor($item_selected->status / 10) == 2)    
    <div class="row header-container justify-content-center subject-line_B">
    @else
    <div class="row header-container justify-content-center">
    @endif
        <div class="header">
            <h1>{{ $item_selected->first_name }} {{ $item_selected->last_name }}</h1>
        </div>
        <!-- dd(Auth::user()) }}--> 
    </div>

    @if($layout == 'show')
        <div class="container-fluid mt-4">
            <div class="row">

                <section class="col-md-4">
                
                <div class="card mb-3">
                    <div>
                        <img src="{{ asset('images/pobuna1.jpeg') }}" class="w-2_12 mb-8 shadow-xl" alt="top_logo">
                    </div>
                    <div class="card-body">
                        <div class="container-show">
                            <div class="mb-2">
                                <label>Ime</label>
                                <input value="{{ $item_selected->first_name }}" readonly name="first_name" type="text" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label>Prezime</label>
                                <input value="{{ $item_selected->last_name }}" readonly name="last_name" type="text" class="form-control subject-line">
                            </div>
                            @if($item_selected->sobriquet != NULL)
                            <div class="mb-3">
                                <label>Nadimak</label>
                                <input value="{{ $item_selected->sobriquet }}" readonly name="sobriquet" type="text" class="form-control">
                            </div>
                            @endif
                        </div>
                        
                        <div class="container-show">
                            <div class="mb-3">
                                <label>G. Rodj.</label>
                                <input value="{{ strtok($item_selected->date_of_birth, '-') }}" readonly name="date_of_birth" type="text" class="form-control">
                            </div>
                            @if($location != NULL)
                            <div class="mb-3-half" value="{{ $location->zip }}">
                                <label>Lokacija</label>
                                <input value="{{ $location->zip }} {{ $location->name }}" readonly name="location" type="text" class="form-control">
                            </div>
                            @endif
                        </div>
                        @if($party != NULL)
                            <div class="mb-3">
                                <label>Organizacija</label>
                                <input value="{{ $party->name }}" readonly name="party" type="text" class="form-control">
                            </div>
                        @endif

                        <div class="container-show">
                            <div class="mb-3-60prc">
                                <label>Zanimanje</label>
                                <input value="{{ $item_selected->occupation }}" readonly name="occupation" type="text" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Pozicija</label>
                                <input value="{{ $item_selected->position }}" readonly name="position" type="text" class="form-control">
                            </div>
                        </div>

                        @if($item_selected->description != null)
                        <div class="mb-3">
                            <label>Description</label>
                            <!--textarea readonly name="description" cols="22" rows="3" class="form-control">{-!! $item_selected->description !!}</textarea-->
                            <div class="fake-textarea" style="max-height: 130px; height: auto;">{!! $item_selected->formatted_description !!}</div>
                        </div>
                        @endif
                        
                        <div class="container-show">
                            <div class="mb-3-half">
                                <label>Email</label>
                                <input value="{{ $item_selected->email }}" readonly name="email" type="text" class="form-control">
                            </div>
                            @if($item_selected->phone != null) 
                                <div class="mb-3-half">
                                    <label>Telefon</label>
                                    <input value="*******{{ substr($item_selected->phone, -3) }}" readonly name="phone" type="text" class="form-control">
                                </div>
                            @endif
                        </div>

                    <div class="dropdown-divider"></div>
                        <div class="mb-3">
                        @auth
                            @if (auth()->user() && isset(Auth::user()->id) && auth()->user()->hasVerifiedEmail())
                                <a id="corrBtn" class="btn btn-sm btn-outline-primary toolbar-btn">
                                    Correct/Add (some) more info about {{ $item_selected->first_name }}&nbsp;{{ $item_selected->last_name }}
                                </a>
                            @elseif (!auth()->user()->hasVerifiedEmail())
                                <button class="btn btn-sm btn-outline-secondary toolbar-btn" disabled title="Verify your email to unlock">
                                    Correct/Add (some) more info about {{ $item_selected->first_name }}&nbsp;{{ $item_selected->last_name }} ...(Verification Required)
                                </button>
                            @endif
                        @else
                                <button class="btn btn-sm btn-outline-secondary toolbar-btn" disabled title="Verify your email to unlock">
                                    Correct/Add (some) more info about {{ $item_selected->first_name }}&nbsp;{{ $item_selected->last_name }} ... (Login Required)
                                </button>&nbsp;&nbsp;<a href="{{ route('login') }}" class="btn btn-sm btn-info">{{ __('Login') }}</a>    
                        @endauth
                        </div>
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{-- Display the message value from the session --}}
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>

                </div>
                </section>

                <section class="col-md-4">
                <div id="photo-section" class="card mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            @if($images != null) 
                                @foreach($images as $item)
                                    <div class="mb-3">
                                        <!--{-{ print_r(url('')) }}-->
                                        <!--img src="{-{ asset('images/tmp/' . $item->image_name) }}" alt="{-{ $item->image_name }}"-->
                                        <img src="{{ asset('storage/vale_images/' . $item->image_name) }}" class="w-50 mb-8 shadow-xl" alt="{{ $item->image_name }}">
                                        <!--img src="{-{ url('/') . 'storage/app/' . $item->image_name }}" alt="{-{ $item->image_name }}"-->
                                        <!--img src="{-{ env('APP_URL').'/storage'. $item->image_name }}" alt="{-{ $item->image_name }}"-->
                                    </div>        
                                @endforeach
                            @endif
                        </div>
                        <div class="mb-3">
                            <label>Created At</label>
                            <input value="{{ $item_selected->created_at }}" readonly name="created_at" type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Updated At</label>
                            <input value="{{ $item_selected->updated_at }}" readonly name="updated_at" type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            @auth
                                @if ((Auth::user()->id == $item_selected->owner_id) || Auth::user()->id == 1)
                                    <a href="{{ url('/edit/'.$item_selected->id) }}" class="btn btn-sm btn-info">Edit</a>
                                @else
                                    <p>"Only the person who made the following entry is allowed to Edit or Upload Photos (must be logged)"</p> 
                                    <p>"PERMISSION TO EDIT" only to the original creator of record. Otherwise you can Correct/Add/Comment more info...</p>
                                @endif
                                @if (Auth::user()->id == 1)
                                    <a href="{{ url('/destroy/'.$item_selected->id) }}" class="btn btn-sm btn-danger">Delete</a>
                                @endif
                            @else
                                <p>"Only the person who made the following entry is allowed to Edit or Upload Photos (must be logged)"</p> 
                                <p>"PERMISSION TO EDIT" only to the original creator of record. Otherwise you can Correct/Add/Comment more info...</p>
                            @endauth
                        </div>
                    </div>            
                </div>
                </section>

                @if($events != null) 
                <section class="col-md-4">
                <div class="card mb-3">
                    <div>
                        <img src="{{ asset('images/pobuna1.jpeg') }}" class="w-2_12 mb-8 shadow-xl" alt="top_logo">
                    </div>
                @foreach($events as $item)
                <div class="card-body">
                        <div class="container-show">
                                <div class="mb-3">
                                <label>LOKACIJA</label>
                                    <div class="mb-3-half"><input disabled value="{{ $item->zip }} {{ $item->name }}" name="location_name"></div>
                                </div>
                            @if($item->zip != NULL)
                                <div class="mb-3">
                                    <label>KATEGORIJA</label>
                                    <div class="mb-3-half"><input disabled value="{{ $item->category_name }}" name="category_name"></div>
                                </div>
                            @endif
                        </div>

                        <div class="container-show">
                            <div class="mb-3-60prc">
                                <label>Event Name</label>
                                <input value="{{ $item->event_name }}" readonly name="event_name" type="text" class="form-control">
                            </div>
                            @if($item->event_date != NULL)
                                <div class="mb-3-date">
                                    <label>Date</label>
                                    <div class="mb-3"><input disabled value="{{ Carbon\Carbon::parse($item->event_date)->format('M Y') }}"></div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label>Description</label>
                            <!--textarea readonly name="description" cols="22" rows="5" class="form-control">"{-{ $item->description }}"</textarea-->
                            <div class="fake-textarea" style="max-height: 200px; height: auto;">{!! $item->formatted_description !!}</div>
                        </div>
                        <!--div style="height: 1px; background-color: black; margin: 10px 0;"></div-->
                
                </div>
                <div class="dropdown-divider"></div>
                @endforeach
                
                </div>
                </section>
                @endif

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
    <form action="{{ url('/store-corr') }}" method="post" enctype="multipart/form-data">
            @csrf

      <div class="modal-header">  <!--div class="modal-header bg-primary text-white"-->
        <h5 class="modal-title" id="infoModalLabel">Aditional Info...</h5>
      </div>

      <!-- validation Throw Exception. if $request.input validation - if ($errors->any())-->
      @include("layouts.validation_exception")

      <div class="modal-body">
        <p>
          Ovde mozete dati predlog za izmenu podataka (ili unos nedostajucih) i komentar o subjektu. 
          Uneseni podatci nece biti prikazani/ dok ne prodju relevantnu proveru.
          Svakako ove unete podatke zadrzavamo dok se ne provere i otkloni sumlja o zlonamernom unosu.
          Posebna paznja ce se posvetiti korekciji vec postojecih podataka.
        </p>
        <div class="card-body">
            <!-- <h5 class="card-title">Enter the info of the new subject</h5>-->

                <div class="container-show">
                    <input id="valetudinarian_id" hidden name="valetudinarian_id" value={{ $item_selected->id }}>
                    <div class="mb-3 item" data-help-title="Ime Help"
                            data-help-text="Obavezan unos ovog polja">
                        <label>Ime</label><input name="first_name" type="text" value="{{ $item_selected->first_name }}" class="form-control" placeholder="Enter the First Name*">
                    </div>

                    <div class="mb-3 item" data-help-title="Prezime Help"
                            data-help-text="Obavezan unos ovog polja">
                        <label>Prezime</label><input name="last_name" type="text" value="{{ $item_selected->last_name }}" class="form-control" placeholder="Enter the Last Name">
                    </div>

                    <div class="mb-3">
                        <label>Nadimak</label><input name="sobriquet" type="text" value="{{ $item_selected->sobriquet }}" class="form-control" placeholder="Enter the Nadimak">
                    </div>
                </div>

                <div class="container-show">
                    <div class="mb-3">
                        <label>Lokacija</label>
                        @include("location_list-template")
                    
                    </div>
                    <div class="mb-3-date item" data-help-title="Datum Rodjenja Help"
                            data-help-text="Datum Rodjenja nije obavezan, ali je pozeljno je imati zbog slicnih imena. U sistemu prikazujemo samo Godinu Rodjenja, a celi datum nece biti nikome vidljiv.">
                        <label>Datum Rodjenja</label><input name="date_of_birth" type="date" value="{{ $item_selected->date_of_birth }}" class="form-control" placeholder="Enter datum rodjenja">
                    </div>
                </div>

                <div class="container-show">
                    <div class="mb-3-60prc item" data-help-title="Zanimanje Help"
                            data-help-text="Zanimanje/profesija subjekta. Pocnite da kucate zanimanje u polje, pa izaberite nesto od ponudjenog ili unesite novo zanimanje ako ni jedno od ponudjenih ne odgovara.">
                        <label>Zanimanje</label><input name="occupation" type="text" value="{{ $item_selected->occupation }}" class="form-control" placeholder="Enter Profesija*">
                    </div>

                    <div class="mb-3">
                        <label>Pozicija</label><input name="position" type="text" value="{{ $item_selected->opsition }}" class="form-control" placeholder="Enter the Pozicija">
                    </div>
                </div>     
                <div class="container-show">
                    <div class="mb-3-half item" data-help-title="Organizacija Help"
                                        data-help-text="Dali je osoba pripadnik necega (stranke, organizacije...) Obavezno polje, po njemu mozemo grupisati subjekte.">
                        <label>Organizacija</label>
                        <select name="party_id" class="form-control">
                            <option value="">Izaberi Organizaciju*</option>
                            @foreach($parties as $party)
                                @if(isset($item_selected) && $party->id == $item_selected->party_id) 
                                    <option value="{{$party->id}}" selected>{{$party->name}}</option>
                                @else
                                    <option value="{{$party->id}}">{{$party->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3-40prc">
                        <label>Email</label>
                        <input name="email" type="email" value="{{ $item_selected->email }}" class="form-control" placeholder="Enter Email">
                    </div>

                    <div class="mb-3-30prc">
                        <label>Telefon</label>
                        @if($item_selected->phone != null)
                            <input value="*******{{ substr($item_selected->phone, -3) }}" name="phone" type="text" class="form-control" placeholder="Enter Phone">
                        @else
                            <input name="phone" type="text" class="form-control" placeholder="Enter Phone">
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label>Komentar</label>
                        <textarea id="comment" name="comment" cols="40" rows="3" class="form-control" placeholder="Enter Comment"></textarea>
                </div>
                <ul>
                <li>Podatci ce biti podlozni proveri i nece odmah biti prikazani u sistemu.</li>
                <li>Samo korisnik koji je uneo podatke o osobi ih moze odmah menjati opcijom "Edit".</li>
                </ul>
                
        </div>
      
        <div class="modal-footer">
            <!--button type="button" class="btn btn-primary" data-bs-dismiss="modal">Continue</button>
            <button type="button" class="btn btn-sm btn-outline-danger toolbar-btn" data-bs-dismiss="modal">Continue</button-->
            <input type="submit" class="btn btn-info toolbar-btn" value="Save">&emsp;
            <button type="button" class="btn btn-warning btn-outline-primary toolbar-btn" data-bs-dismiss="modal">Cancel</button>    
        </div>

      </div>

    </form>  
    </div>
  </div>
</div>

<!-- ************************************************************* Info Modal ****** -->
<!--@-if($showModal)    if(session('showInfo'))-->
<!--@-if(session('showValetudinarianInfoModal'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          var myModal = new bootstrap.Modal(document.getElementById('infoModal'));
          myModal.show();
      });
    </script>
@-endif-->
<!-- ************************************************************* Info Modal End *** -->
<script>
    (function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const correctionBtn = document.getElementById('corrBtn');
        const saveBtn = document.getElementById('saveBtn');

        // when correction button is pressed
        correctionBtn.addEventListener('click', () => {
            if (!confirm(`Correction. You are going to add data for the this person to Correction. Data will not be shown until system assess their accuracy`)) return;
            //const vale_id = document.getElementById('vale_id').getAttribute('value');   //on a show page if location is shown
            //get_correction(vale_id)
            var myModal = new bootstrap.Modal(document.getElementById('infoModal'));
            myModal.show();
        });
        
    })();

    /*function get_correction(valeID = null) {
        const SITEURL = "{{ url('/') }}";

        $.ajax({
            type:'POST',
            url: SITEURL + '/correctio-ajax',
            //url:"{{ route('valetudinarians.filter') }}",
            data:{
                //_token: "{{ csrf_token() }}",
                ID:valeID,
                type:'data-for-correction'
            },
            success:function(data){
                if(data){
                    toastr.info('Fill known data or correct existing one', 'Correction');
                    
                    $('#last_corr').first().after(data.html);
                }else{
                    toastr.info('Oops Something went wrong" error on processing no matter ...', 'Correction');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading location list:', error);
            }
        });
    }*/
    //********************************************** End***
</script>

@endsection   

