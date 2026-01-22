<div id="data-output-append">
<div id="data-output">

<div class="card mb-3">
    <img src="{{ asset('images/pobuna1.jpeg') }}" class="w-2_12 mb-8 shadow-xl" alt="top_logo">
    <div class="card-body">
        <div class="list_container">
            <div class="fixed">
                <h5 class="card-title">Lista Subjekata</h5>
                <p class="card-text">You will find here all the info about subjects in the system</p>
            </div>
            <div class="flex-item">
                <!--form action="{***{ url('/valetudinarian-ajax') }}" method="GET"-->
                    <input id="search" type="text" name="search"/>    <!--required/-->
                    <!--button id="search_btn" type="button">Search</button-->
                    <img id="search_img" src="{{ asset('images/search-icon-no-background-hd-260.png') }}" alt="Search" width=3% height=auto />
                <!--/form-->
            </div>    
        </div>
        <!--<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>-->
        <div class="col">
            <table id="thetable" class="table">
            <thead>
                <tr>
                    @if(isset($layout) && $layout == 'index')
                        <th scope="col">&nbsp;&nbsp;&nbsp;</th>
                    @endif
                    <th scope="col">Ime</th>
                    <th scope="col">Prezime</th>
                    <th scope="col">G. Rodj.</th>
                    <th scope="col">Zanimanje</th>
                    @if(isset($layout) && $layout == 'index')<th scope="col">Pozicija</th>@endif
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
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($valetudinarians as $valetudinarian)
                <tr @if(isset($valetudinarian->statusGroup))class="status-{{ $valetudinarian->statusGroup }}"@endif>
                    @if(isset($layout) && $layout == 'index')
                    <td>
                        <a href="{{ url('/show/'.$valetudinarian->id.'#photo-section') }}">
                        <img src="{{ asset('storage/vale_images/' . $valetudinarian->image_name) }}" class="w-4" alt="{{ $valetudinarian->image_name }}">
                        </a>
                    </td>
                    @endif
                    <td>
                        <a href="{{ url('/show/'.$valetudinarian->id) }}">
                            {{ $valetudinarian->first_name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ url('/show/'.$valetudinarian->id) }}">
                            {{ $valetudinarian->last_name }}
                        </a>
                    </td>
                    <td>{{ strtok($valetudinarian->date_of_birth, '-') }}</td>
                    <td><div class="col-200-ellipsis-auto">{{ $valetudinarian->occupation }}</div></td>
                    @if(isset($layout) && $layout == 'index')<td><div class="col-200-ellipsis-auto">{{ $valetudinarian->position }}</div></td>@endif
                    <td><div class="col-250-ellipsis-auto">{{ $valetudinarian->party_name }}</div></td>
                    <td>{{ $valetudinarian->location_name }}</td>
                    <!--td>{***{ $equipment->availability }}</td-->
                    <td>
                        @auth             
                            @if ((Auth::user()->id == $valetudinarian->owner_id) || Auth::user()->id == 1)
                                <!--@***if (isset(Auth::user()->role_id) && Auth::user()->role_id == 1)-->
                                <!--@***if (Auth::user())-->
                                <!--a href="{ { url('/calendar-equ/'.$equipment->id) }}" class="border-b-2 pb-2 border-dotted italic text-green-500">
                                    <img src="{ { asset('images/Calendar-icon.png') }}" alt="Calendar" width=5% height=auto />
                                </a>
                                &emsp;--> 
                                <!--<a href="#" class="btn btn-sm btn-info">Show</a>-->
                                <a href="{{ url('/edit/'.$valetudinarian->id.'?page='.optional($valetudinarians)->currentPage() ?? 1) }}" class="btn btn-sm btn-info">Edit</a>
                                <!--a href="{-{ url('/edit/'.$valetudinarian->id.'?page='.$valetudinarians->currentPage()) }}" class="btn btn-sm btn-info">Edit</a-->
                            @endif

                            @if (Auth::user()->id == 1)
                                <a href="{{ url('/destroy/'.$valetudinarian->id.'?page='.optional($valetudinarians)->currentPage() ?? 1) }}" class="btn btn-sm btn-danger">Delete</a>
                                <!--<a href="" class="btn btn-sm btn-danger">Delete</a>-->
                            @endif
                        <!--@ else
                            <p>Please login, to edit items.</p-->
                        @endauth
                    </td>
                </tr>
            @endforeach
            </tbody>
            </table>

        </div>
    </div>
</div>
@if(is_object($valetudinarians) && method_exists($valetudinarians, 'links'))
    <div id="pagination-links" class="paginate_div">{{ $valetudinarians->links() }}</div>
@endif
<div id="layout" data-layout="{{ $layout }}">

<script src="{{ asset('js/list-data.js') }}"></script>
<script type="text/javascript">
    $('#region_filter').on('change', function() {
        const regionSelected = $(this).val();
        const search_str = $('#search').val();
        const partyID = $('#party_filter').val();
        const filterRoute = "{{ route('valetudinarians.filter') }}";
        const urlRoute = "{{ url('/') }}" + "/region-ajax";

        fetch_select_cities(urlRoute, regionSelected, 'location_filter', null)
        get_data_output(filterRoute, null, null, partyID, regionSelected, null, search_str, null)
    });

    $('#party_filter, #location_filter').on('change', function() {
        const search_str = $('#search').val();
        const partyID = $('#party_filter').val();
        const locationID = $('#location_filter').val();
        const regionSelected = $('#region_filter').val();
        const filterRoute = "{{ route('valetudinarians.filter') }}";

        get_data_output(filterRoute, null, null, partyID, regionSelected, locationID, search_str, null)
    });
    
    $('#search_img').click(function() {
        const search_str = $('#search').val();
        const partyID = $('#party_filter').val();
        const locationID = $('#location_filter').val();
        const regionSelected = $('#region_filter').val();
        const filterRoute = "{{ route('valetudinarians.filter') }}";
        
        get_data_output(filterRoute, null, null, partyID, regionSelected, locationID, search_str, null)
        $('#search').val(null);
    });

</script>
</div>
</div>