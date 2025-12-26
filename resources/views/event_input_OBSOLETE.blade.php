<div id="data-event-append">
<div id="data-event">

                        <div class="mb-3 item" data-help-title="KATEGORIJA Help"
                            data-help-text="This box represents the first category. It may contain photos, files, or text data associated with section one.">
                            <label>KATEGORIJA</label>
                                <div class="mb-3">
                                    <select id="category_id" name="category_id" class="form-control">
                                        @if($layout != 'edit' || (isset($item_selected) && $item_selected->category_id == null))
                                            <option selected value=''>Izaberi Kategoriju - if applicable</option>
                                        @endif 
                                        @foreach($categories as $item)
                                            @if(isset($item_selected) && ($item->id == $item_selected->category_id))  
                                                <option value="{{$item->id}}" selected>{{$item->category_name}}</option>
                                            @endif 
                                            <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>    
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="mb-3 item" data-help-title="Event Name Help"
                            data-help-text="This box represents the first category. It may contain photos, files, or text data associated with section one.">
                            <label>Event Name</label>
                            @if($layout != 'add')
                                <input @if($layout == 'edit') value="{{ $item_selected->event_name }}"@endif id="event_name" name="event_name" type="text" class="form-control" placeholder="Enter the Event Name*">
                            @else
                                <div id="div_select" class="mb-3">
                                    <select id="ev_id" name="event_id" class="form-control">
                                        <option selected disabled hidden style='display: none' value=''>Choose Event*</option>
                                        @foreach($events as $item)
                                            @if(isset($drop_item_selected) && $item->id == $drop_item_selected->get('event_ID')) 
                                                <option value="{{ $item->id }}" selected>{{ $item->event_name }}</option>
                                            @else  
                                                <option value="{{ $item->id }}">{{ $item->event_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            @endif 
                        </div>
                        <div class="mb-3">
                                <label>Lokacija</label>
                                @if($layout == 'make_famous' || $layout == 'create_vale_event')
                                    <!--@-include("location_list2")-->
                                    @include("location_list-template2")
                                @else
                                    <!--@-include("location_list")-->
                                    @include("location_list-template")
                                @endif
                        </div>
                        @if($layout != 'add')        <!--for "add" we don't need them--> <!--ADDING VALETUDINARIAN TO EVENT (For: Create, Edit)-->
                            <div class="mb-3-date item" data-help-title="Date Help"
                            data-help-text="Datum Dogadjaja nije obavezan, ali ako postoji, pozeljno je uneti bar orijentacijoni datum. Neki dogadjaji su trajali duze vremena, ali pokusajte uneto bar orijentacioni pocetek dogadjaja, a u opisu mozete navesti celo trajanje.">
                                <label>Date</label>
                                <!--{--{ Form::text('date', '', array('id' => 'datepicker')) }}-->
                                <input @if($layout == 'edit') value="{{ $item_selected->event_date }}"@endif name="event_date" type='date' class="form-control" placeholder="Enter Date*">
                            </div>    

                            <div class="mb-3 item" data-help-title="Description Help"
                            data-help-text="Ovde unosite opis dogadjaja. Mozete uneti i linkove na stranice, kao i copy/paste text iz drugih izvora. Pokusajte da jasno unesete sve podatke vezane za dogadaj koji se tice subjekta za koji je vezan. It may contain photos, files, or text data associated with section one.">
                                <label>Description</label>
                                <textarea id="description" name="description" cols="40" rows="7" class="form-control" @if($layout == 'make_famous')placeholder="{{ $image_guess->description }}"
                                @else placeholder="Enter Description"@endif>@if($layout == 'edit')"{{ $item_selected->description }}"@endif</textarea>
                            </div>

                            @if($layout == 'edit')
                                <div class="mb-3">
                                    <a href="{{ url('/upload_event_img/'.$item_selected->id) }}">Upload Photo for: {{ $item_selected->event_name }}</a>
                                </div>
                            @else
                                <div class="mb-3 item" data-help-title="Upload Photo Help"
                                    data-help-text="This box represents the first category. It may contain photos, files, or text data associated with section one.">
                                    <label>Upload Event's Photo</label><br>
                                    @if($layout == 'make_famous' || $layout == 'create_vale_event')
                                        <input type="file" name="image2" id="image2">
                                    @else
                                        <input type="file" name="image" id="image">
                                    @endif
                                </div>
                            @endif 
                        @endif
                
</div>
</div>
                        
 