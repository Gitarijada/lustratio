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
                    <input id="search" type="text" name="search">    <!--required/-->
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
                    <th scope="col">&nbsp;&nbsp;&nbsp;</th>
                    <th scope="col">Ime</th>
                    <th scope="col">Prezime</th>
                    <th scope="col">G. Rođ.</th>
                    <th scope="col">Zanimanje</th>
                    <th scope="col">Pozicija</th>
                    <th scope="col">
                        <select id="party_filter" name="party_filter" class="form-control">
                            <option value="">Izaberi Pripadnost</option>
                            @foreach($parties as $item)  
                                @if($item->id == $drop_item_selected->get('party_ID'))  
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
                                    @if($item->region == $drop_item_selected->get('region')) 
                                        <option value="{{ $item->region }}" selected>{{ $item->region }}</option>
                                    @else  
                                        <option value="{{ $item->region }}">{{ $item->region }}</option>
                                    @endif
                                @endforeach
                            </select>
                            
                            <select id="location_filter" name="location_filter" class="form-control">
                                <option selected value="">Izaberi Grad</option>
                                @foreach($cities as $item)
                                    @if($item->city_zip == $drop_item_selected->get('location_ID')) 
                                        <option value="{{ $item->city_zip }}" selected>{{ $item->city }}</option>
                                    @else  
                                        <option value="{{ $item->city_zip }}">{{ $item->city }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
            @foreach($valetudinarians as $valetudinarian)
                <tr>
                    <td>
                        <a href="{{ url('/show/'.$valetudinarian->id.'#photo-section') }}">
                        <img src="{{ asset('storage/vale_images/' . $valetudinarian->image_name) }}" class="w-4" alt="{{ $valetudinarian->image_name }}">
                        </a>
                    </td>
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
                    @if($layout == 'index')<td><div class="col-200-auto">{{ $valetudinarian->position }}</div></td>@endif
                    <td><div class="col-250-ellipsis-auto">{{ $valetudinarian->party_name }}</div></td>
                    <td>{{ $valetudinarian->location_name }}</td>
                    <!--td>{***{ $equipment->availability }}</td-->
                    <td>
                        @auth
                        @if (auth()->user() && isset(Auth::user()->id))
                            @if ((Auth::user()->id == $valetudinarian->owner_id) || Auth::user()->id == 1)
                                <!--@***if (isset(Auth::user()->role_id) && Auth::user()->role_id == 1)-->
                                <!--@***if (Auth::user())-->
                                <!--a href="{ { url('/calendar-equ/'.$equipment->id) }}" class="border-b-2 pb-2 border-dotted italic text-green-500">
                                    <img src="{ { asset('images/Calendar-icon.png') }}" alt="Calendar" width=5% height=auto />
                                </a>
                                &emsp;--> 
                                <!--<a href="#" class="btn btn-sm btn-info">Show</a>-->
                                <a href="{{ url('/edit/'.$valetudinarian->id.'?page='.optional($valetudinarians)->currentPage() ?? 1) }}" class="btn btn-sm btn-info">Edit</a>
                            @endif

                            @if (Auth::user()->id == 1)
                                <a href="{{ url('/destroy/'.$valetudinarian->id.'?page='.optional($valetudinarians)->currentPage() ?? 1) }}" class="btn btn-sm btn-danger">Delete</a>
                                <!--<a href="" class="btn btn-sm btn-danger">Delete</a>-->
                            @endif
                        <!--@ else
                            <p>Please login, to edit items.</p-->
                        @endif
                        @endauth
                    </td>
                </tr>
            @endforeach
            </tbody>
            </table>

        </div>
    </div>
</div>
<div id="pagination-links" class="paginate_div">{{ $valetudinarians->links() }}</div>
<div id="layout" data-layout="{{ $layout }}">
<!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script-->

<!-- <select> dropdown menu "type-to-filter" feature -->
<!--<select class="searchable-dropdown" name="country"> </select>-->
<!--<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>-->

<script type="text/javascript">
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#region_filter').on('change', function() {
        var regionSelected = $(this).val();
        //var regionSelected = $('#region_filter').val();
        var search_str = $('#search').val();
        var partyID = $('#party_filter').val();
        
        fetch_select_cities(regionSelected, 'location_filter', 'Region')
        fetch_data(1, regionSelected, null, partyID, null, search_str)
    });
    /*$( "#search" ).focus(function() {
        //alert( "Handler for .focus() called." );
        //$( "#search_img" ).focus();
    });*/
    //$('img').bind('click', function(){ alert("it works!") });     //for all images
    $('#search_img').click(function() {
        var search_str = $('#search').val();
        var partyID = $('#party_filter').val();
        var locationID = $('#location_filter').val();
        var regionSelected = $('#region_filter').val();

        fetch_data(1, regionSelected, null, partyID, locationID, search_str)
        $('#search').val(null);
    });

    $('#party_filter, #location_filter').on('change', function() {
        var search_str = $('#search').val();
        var partyID = $('#party_filter').val();
        var locationID = $('#location_filter').val();
        var regionSelected = $('#region_filter').val();

        fetch_data(1, regionSelected, null, partyID, locationID, search_str)
    });

    function fetch_data(page = null, regionSelected = null, eventID = null, partyID = null, locationID = null, search_str = null) {
        var SITEURL = "{{ url('/') }}";
        const ORGIN_REQUEST_URI = "{{ $_SERVER['REQUEST_URI'] }}";
        //const ORGIN_REQUEST_URI = window.location.pathname;
        const layoutElement = document.getElementById('layout');
        const layout = layoutElement.dataset.layout; // original layout (index, create...)
        
        $.ajax({
           type:'POST',
           //url: SITEURL + '/equevent-ajax',
           //url: SITEURL + '/ajaxRequest',
           //url: '/fetch-data?page=' + page,
           url:"{{ route('valetudinarians.filter') }}",
           data:{
                page:page,
                event_ID:eventID,
                party_ID:partyID,
                region:regionSelected,
                location_ID:locationID,
                search_Str:search_str,
                orgin_URI:ORGIN_REQUEST_URI,
                type:'filter'
           },
           success:function(data){
                if(data){
                    if(search_str){
                        displayMessage("Search For: " + search_str + "...");
                    }
                    $('#thetable tr').not(':first').remove();
                    //$('#thetable tr').not(':first').not(':last').remove();
                    var html = '';
                    var user_id = null;
                    if (Number.isInteger(data.user)) { 
                        user_id = data.user;
                    }
                    
                    for(var key in data.valetudinarians) {
                       
                            html += '<tr><td><a href="' + SITEURL + '/show/' + data.valetudinarians[key].id + '#photo-section">' + 
                                '<img src="' + SITEURL + '/storage/vale_images/' + data.valetudinarians[key].image_name + '" class="w-4" alt="' + data.valetudinarians[key].image_name + '"></a>' + 
                                        '</td><td><a href="' + SITEURL + '/show/' + data.valetudinarians[key].id + '">' + data.valetudinarians[key].first_name + '</a>' + 
                                        '</td><td><a href="' + SITEURL + '/show/' + data.valetudinarians[key].id + '">' + data.valetudinarians[key].last_name + '</a>' + 
                                        //'</td><td>strtok(' + data.valetudinarians[key].date_of_birth + ', '-')' +
                                        '</td><td>' + parseInt(data.valetudinarians[key].date_of_birth) + 
                                        //'</td><td>' + data.valetudinarians[key].date_of_birth + 
                                        '</td><td class="col-200-ellipsis-auto">' + data.valetudinarians[key].occupation + '</div></td>';
                            if (layout == 'index') {     //data.layout == 1 => layout = 'index'
                                html += '<td><div class="col-200-auto">' + position + '</div></td>';
                            }    
                            html += '<td class="col-250-ellipsis-auto">' + data.valetudinarians[key].party_name + 
                                        '</td><td>' + data.valetudinarians[key].location_name + '</td><td>';

                            if (user_id == data.valetudinarians[key].owner_id || user_id == 1) {
                                html += '<a href="' + SITEURL + '/edit/' + data.valetudinarians[key].id + '" class="btn btn-sm btn-info">Edit</a> ';
                                if (user_id == 1) {
                                    html += '<a href="' + SITEURL + '/destroy/' + data.valetudinarians[key].id + '" class="btn btn-sm btn-danger">Delete</a>'; 
                                }
                            } //else {
                                //html += '<p>Please login, to edit items.</p>';
                            //}
                            html += '</td></tr>';
                            //alert(data.links + '   ENDE');
                                            
                    }
                    $('#thetable tr').first().after(html);

                    //To change the href of $paginatedData->links() (href links in pages)
                    /*let page_variables = '';
                    let arr_page_var = [];
                    if (partyID) { arr_page_var.push('party_ID='+partyID); }
                    if (locationID) { arr_page_var.push('location_ID='+partyID); }
                    if (search_str) { arr_page_var.push('search_Str='+partyID); }
                    arr_page_var.forEach(function(element) {
                        if (page_variables.length === 0) { page_variables += '?'; }
                        page_variables += element;
                    });
                    if (page_variables.length !== 0) { 
                        page_variables += '&page=';
                        data.links = data.links.replaceAll("?page=", page_variables);
                        //let modifiedLinks = data.links.split("?page=").join(page_variables);
                        console.log(data.links);
                    }*/
                    
                    $('#pagination-links').html(data.links);
                    //add EventListener because is ajax call. If we need to perform something (after page button is press) before go to button's href link 
                    /*const myGo = document.getElementById('pagination-links');
                    myGo.addEventListener('click', function(event) {
                        alert('-->listener');
                    });*/
                }else{
                    $('#search').val(null);
                }
           }
        });
    }

    //**********************
    function fetch_data_prev(eventID = null, partyID = null, locationID = null, search_str = null) {
        var SITEURL = "{{ url('/') }}";
        
        $.ajax({
           type:'POST',
           //url: SITEURL + '/equevent-ajax',
           //url: SITEURL + '/ajaxRequest',
           url:"{{ route('valetudinarians.filter') }}",
           data:{
                event_ID:eventID,
                party_ID:partyID,
                location_ID:locationID,
                search_Str:search_str,
                type:'filter'
           },
           success:function(data){
                if(data){
                    if(search_str){
                        displayMessage("Search For: " + search_str + "...");
                    }
                    $('#thetable tr').not(':first').remove();
                    //$('#thetable tr').not(':first').not(':last').remove();
                    var html = '';
                    var user_id = null;
                    for(var i = 0; i < data.length; i++) {

                        if (Number.isInteger(data[i])) { 
                            user_id = data[i];
                            continue;
                        }
                            html += '<tr><td><a href="' + SITEURL + '/show/' + data[i].id + '">' + data[i].first_name + '</a>' + 
                                        '</td><td><a href="' + SITEURL + '/show/' + data[i].id + '">' + data[i].last_name + '</a>' + 
                                        //'</td><td>strtok(' + data[i].date_of_birth + ', '-')' +
                                        '</td><td>' + parseInt(data[i].date_of_birth) + 
                                        //'</td><td>' + data[i].date_of_birth + 
                                        '</td><td>' + data[i].occupation +
                                        '</td><td>' + data[i].position +
                                        '</td><td>' + data[i].party_name + 
                                        '</td><td>' + data[i].location_name;
                                        //'</td><td>' + data[i].availability;

                            html += '</td><td>';

                            if (user_id == data[i].owner_id || user_id == 1) {
                                html += '<a href="' + SITEURL + '/edit/' + data[i].id + '" class="btn btn-sm btn-info">Edit</a> ';
                                if (user_id == 1) {
                                    html += '<a href="' + SITEURL + '/destroy/' + data[i].id + '" class="btn btn-sm btn-danger">Delete</a>'; 
                                }
                            } //else {
                                //html += '<p>Please login, to edit items.</p>';
                            //}
                            html += '</td></tr>';
                                            
                    }
                    $('#thetable tr').first().after(html);
                }else{
                    $('#search').val(null);
                }
           }
        });
    }

    function fetch_select_cities(valueID = null, element = null, message = null) {
        var SITEURL = "{{ url('/') }}";
        
        $.ajax({
           type:'POST',
           url: SITEURL + '/region-ajax',
           data:{
                value_ID:valueID,
                type:'select-region-list'
           },
           success:function(data){
                if(data){
                    if(message){
                        displayMessage(message + ' ' + valueID + ' selected');
                    }
                    $('#' + element + ' option').not(':first').remove();
                    //$('#location_filter option').not(':first').remove();
                    //$('#thetable tr').not(':first').not(':last').remove();
                    var html = '';
                    //var html = '<option selected disabled hidden style="display: none" value="">Choose Event</option>';   //this stay if not(':first')
                    for(var i = 0; i < data.length; i++) {
                        html += '<option value="' + data[i].id + '">' + data[i].name + '</option>'; 
                    }
                    $('#' + element + ' option').first().after(html);
                    //$('#location_filter option').first().after(html);
                }else{
                    displayMessage(message + " went wrong !!!");
                }
           }
        });
    }

    function displayMessage(message) {
        toastr.success(message, 'Event');            
    }

</script>
