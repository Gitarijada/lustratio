<div id="data-output-append">
<div id="data-output">

<div class="card mb-3">
    <img src="{{ asset('images/pobuna.jpeg') }}" class="w-2_12 mb-8 shadow-xl" alt="top_logo">
    <div class="card-body">
        <div class="list_container">
            <div class="fixed">
                <h5 class="card-title">List of Valetudinarians</h5>
                <p class="card-text">You will find here all the info about subjects in the system</p>
            </div>
            <div class="flex-item">
                <input id="search" type="text" name="search"/>      <!--required/-->
                <img id="search_img" src="{{ asset('images/search-icon-no-background-hd-260.png') }}" alt="Search" width=3% height=auto />
            </div>    
        </div>
    <table id="thetable" class="table">
        <thead>
            <tr>
                @if($layout != 'show')
                    <th scope="col">Select</th>
                @endif
                <th scope="col">Name</th>
                <th scope="col">G. Rodj.</th>
                @if(($layout == 'createfromvaleid') OR ($layout == 'show'))
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
                    <th scope="col"></th>
                @endif

            </tr>
        </thead>
        <tbody>
        @foreach($valetudinarians as $valetudinarian)
            <tr>
                @if($layout != 'show')
                    <td><input type="checkbox" @if(isset($valetudinarian->vale_selected) && $valetudinarian->vale_selected == 1) checked @endif @if($valetudinarian->used_by_other == 1) disabled @endif name="vale_selected[]" value="{{ $valetudinarian->id }}"></td>
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

                    <td>
                        @auth
                            @if ((Auth::user()->id == $valetudinarian->owner_id) || Auth::user()->id == 1)
                                <a href="{{ url('/edit/'.$valetudinarian->id.'?page='.optional($valetudinarians)->currentPage() ?? 1) }}" class="btn btn-sm btn-info">Edit</a>
                            @endif

                            @if (Auth::user()->id == 1)
                                <a href="{{ url('/destroy/'.$valetudinarian->id.'?page='.optional($valetudinarians)->currentPage() ?? 1) }}" class="btn btn-sm btn-danger">Delete</a>
                            @endif
                        @endauth
                    </td>
                @endif
                                
            </tr>
        @endforeach
        </tbody>
    </table>
    </div>

</div>
@if(is_object($valetudinarians) && method_exists($valetudinarians, 'links'))
    <div id="pagination-links" class="paginate_div">{{ $valetudinarians->links() }}</div>
@endif

<script src="{{ asset('js/list-data.js') }}"></script>
<script type="text/javascript">
    $('#region_filter').on('change', function() {
        const regionSelected = $(this).val();
        const eventID = $('#ev_id').val();
        const search_str = $('#search').val();
        const partyID = $('#party_filter').val();
        const checkedCheckboxes = document.querySelectorAll('input[name="vale_selected[]"]:checked');
        const vale_selected = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);
        const filterRoute = "{{ route('vale.filter') }}";
        const urlRoute = "{{ url('/') }}" + "/region-ajax";
        
        fetch_select_cities(urlRoute, regionSelected, 'location_filter', null)
        get_data_output(filterRoute, eventID, null, partyID, regionSelected, null, search_str, vale_selected)
    });

    $('#party_filter, #location_filter').on('change', function() {
        const search_str = $('#search').val();
        const eventID = $('#ev_id').val();
        const partyID = $('#party_filter').val();
        const locationID = $('#location_filter').val();
        const regionSelected = $('#region_filter').val();
        const checkedCheckboxes = document.querySelectorAll('input[name="vale_selected[]"]:checked');
        // 2. Convert the NodeList to an array and map their values
        const vale_selected = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);
        const filterRoute = "{{ route('vale.filter') }}";

        if (eventID == null) { 
            toastr.success("An event is not chosen", 'Event'); 
        }

        get_data_output(filterRoute, eventID, null, partyID, regionSelected, locationID, search_str, vale_selected)
    });
    
    $('#search_img').click(function() {
        const search_str = $('#search').val();
        const eventID = $('#ev_id').val();
        const partyID = $('#party_filter').val();
        const locationID = $('#location_filter').val();
        const regionSelected = $('#region_filter').val();
        const checkedCheckboxes = document.querySelectorAll('input[name="vale_selected[]"]:checked');
        const vale_selected = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);
        const filterRoute = "{{ route('vale.filter') }}";

        if (eventID == null) { 
            toastr.success("An event is not chosen", 'Event'); 
        } 
        
        get_data_output(filterRoute, eventID, null, partyID, regionSelected, locationID, search_str, vale_selected)
        $('#search').val(null);
    });

</script>
</div>
</div>





