<div id="data-confirm">

<div class="card mb-3">
<div class="row">               
    <section class="col-md-8">
    <img src="{{ asset('images/pobuna.jpeg') }}" class="w-1_12 mb-8 shadow-xl" alt="top_logo">
    <div class="card-body">
        <div class="list_container">
            <div class="fixed">
                <h5 class="card-title">Confirm and Save</h5>
                <p class="card-text">Following persons will be attached to the Event</p>
            </div>   
        </div>
    <table id="thetable" class="table">
        <thead>
            <tr>
                @if($layout == 'show')
                    <th scope="col">Select</th>
                @endif
                <th scope="col">Name</th>
                <th scope="col">G. Rodj.</th>
                @if($layout == 'show')
                    <th scope="col">Organizacija</th>
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
            </tr>
        </thead>
        <tbody>
        @foreach($valetudinarians as $valetudinarian)
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
                        <input disabled value="{{ Carbon\Carbon::parse($event->event_date)->format('M Y') }}">
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





