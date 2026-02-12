<div id="data-confirm">

@php
    $limit = 4;
@endphp
<div class="card mb-3">
<div class="row">               
    <section class="col-md-8">
    <img src="{{ asset('images/pobuna.jpeg') }}" class="w-1_12 mb-8 shadow-xl" alt="top_logo">
    <div class="card-body">
        <div class="list_container">
            <div class="fixed">
                <h5 class="card-title">Confirm and Save&nbsp;&nbsp;&nbsp;&nbsp;
                    @if(count($valetudinarians) > $limit) {{-- Check if the total count exceeds 5 --}}
                        <span style="color: red; font-weight: bold;"> (ONLY {{ $limit }} ENTRIES ARE ALLOWED AT ONCE)</span>
                    @endif
                </h5>
                <p class="card-text">Following persons will be attached to the Event. You may, enter separately additional Event Description for them.</p>
            </div>   
        </div>
    <table id="thetable" class="table">
        <thead>
            <tr>
                @if($layout == 'show')
                    <th scope="col">Select</th>
                @endif
                <th scope="col" class="col-150">Name</th>
                <th scope="col">G. Rođ.</th>
                @if($layout == 'show')
                    <th scope="col" class="col-150">Organizacija</th>
                    <th scope="col">Mesto</th>
                @else
                    <th scope="col">
                        <select id="party_filter" name="party_filter" class="form-control">
                            <option value="">Izaberi Pripadnost</option>
                            @foreach($parties as $item)  
                                @if(isset($drop_item_selected) && $item->id == $drop_item_selected->get('party_ID'))  
                                    <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                @else  
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </th>
                    <th scope="col">
                        <div id="div_select" style="border: 2px solid lightgray;">
                            <select id="region_filter" name="region_filter" class="form-control">
                                <option selected value="">Izaberi Region</option>
                                @foreach($regions as $item)
                                    @if(isset($drop_item_selected) && $item->region == $drop_item_selected->get('region')) 
                                        <option value="{{ $item->region }}" selected>{{ $item->region }}</option>
                                    @else  
                                        <option value="{{ $item->region }}">{{ $item->region }}</option>
                                    @endif
                                @endforeach
                            </select>
                                            
                            <select id="location_filter" name="location_filter" class="form-control">
                                <option selected value="">Izaberi Grad</option>
                                @foreach($cities as $item)
                                    @if(isset($drop_item_selected) && $item->city_zip == $drop_item_selected->get('location_ID')) 
                                        <option value="{{ $item->city_zip }}" selected>{{ $item->city }}</option>
                                    @else  
                                        <option value="{{ $item->city_zip }}">{{ $item->city }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </th>
                @endif
                @if($layout != 'show')
                    <th scope="col">At Disposition</th>
                @endif
                <th scope="col">Add Description</th>
            </tr>
        </thead>
        <tbody>
        @foreach($valetudinarians as $valetudinarian)
            {{-- Exit the loop if we have already displayed $limit(5) items --}}
            @if($loop->index >= $limit) @break @endif

            <tr>
                @if($layout == 'show')
                    <td><input type="checkbox" @if(isset($valetudinarian->vale_selected) && $valetudinarian->vale_selected == 1) checked @endif @if(isset($valetudinarian->used_by_other) && $valetudinarian->used_by_other == 1) disabled @endif name="vale_selected[]" value="{{ $valetudinarian->id }}"></td>
                @endif
                <td>
                    <a href="{{ url('/show/'.$valetudinarian->id) }}">{{ $valetudinarian->first_name }} {{ $valetudinarian->last_name }}</a>
                </td>
                <td>{{ strtok($valetudinarian->date_of_birth, '-') }}</td>
                <td>{{ $valetudinarian->party_name }}</td>
                <td>{{ $valetudinarian->location_name }}</td>

                @if($layout != 'show')  
                    @if($valetudinarian->used_by_other == 0)
                        <td><img src="{{ asset('images/gui_check_yes_icon_157194.png') }}" alt="True" width=12% height=auto /></td>                               
                    @else
                        <td><img src="{{ asset('images/gui_check_no_icon_157196.png') }}" alt="False" width=12% height=auto /></td>
                    @endif
                @endif

                <td><div><textarea id="vev_desc_{{ $valetudinarian->id }}" 
                                name="vev_description[{{ $valetudinarian->id }}]" 
                                cols="40" rows="3" 
                                class="form-control vev-textarea" 
                                maxlength="800" 
                                placeholder="Enter additional Event Description for this particular subject"></textarea>
                        <!-- Progress Bar Container -->
                        <!--div class="progress mt-2" style="height: 10px;">
                            <div id="description-bar_{-{ $valetudinarian->id }}" 
                                class="progress-bar bg-success" 
                                role="progressbar" 
                                style="width: 0%;" 
                                aria-valuenow="0" 
                                aria-valuemin="0" 
                                aria-valuemax="800">
                            </div>
                        </div-->
                <small class="form-text text-muted">
                    <span id="vev_char-count_{{ $valetudinarian->id }}">800</span> characters remaining.
                    <!--span id="char-count">0</span> / 800 characters -->
                </small>
                </div></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
    </section>
    <section class="col-md-4">
        <img src="{{ asset('images/pobuna.jpeg') }}" class="w-1_12 mb-8 shadow-xl" alt="top_logo">
        @if($layout == 'show')
            <div class="dropdown-divider"></div>
                <div class="mb-3">
                    <label>Event Name</label>
                    <input id="event_id" type="hidden" name="event_id" value={{ $event->id }}>
                    <div><input disabled value="{{ $event->event_name }}" class="form-control"></div>
                </div>

                @if(isset($event->event_date))
                    <div class="mb-3-date">
                        <label>Date</label>
                        <input disabled value="{{ Carbon\Carbon::parse($event->event_date)->format($event->precision_date ?? 'd M Y') }}">
                    </div>
                @endif        
            <div class="mb-3">
                <label>Description</label>
                <textarea disabled name="event_description" cols="20" rows="3" class="form-control">{{ $event->description }}</textarea>
            </div>
        @endif             
    </section>  
</div>
</div>

</div>