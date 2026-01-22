<div id="data-output-append">
<div id="data-output">

<div class="card mb-3">
    <img src="{{ asset('images/protest.jpeg') }}" class="w-2_12 mb-8 shadow-xl" alt="top_logo">
    <div class="card-body">
        <div class="list_container">
            <div class="fixed">
                <h5 class="card-title">List of Events</h5>
                <p class="card-text">You will find here all the info about subjects in the system</p>
            </div>
            <div class="flex-item">
                <!--form action="{{ url('/crud-event-ajax') }}" method="GET"-->
                    <input id="search" type="text" name="search" required/>
                    <img id="search_img" src="{{ asset('images/search-icon-no-background-hd-260.png') }}" alt="Search" width=3% height=auto />
                    <!--button id="search_btn" type="button">Search</button-->
                <!--/form-->
            </div>    
        </div><!--p id="test_json"></p-->
        <!--<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>-->
        <div class="col">
            <table id="thetable" class="table">
            <thead>
                <tr>
                    <th scope="col">Event Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Date</th>
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
                    <th scope="col">Subject</th>
                    <th scope="col">
                        <select id="category_filter" name="category_filter" class="form-control">
                            <option selected value="">Kategorija</option>
                            @foreach($categories as $item)
                                @if(isset($drop_item_selected) && $item->id == $drop_item_selected->get('category_ID'))  
                                    <option value="{{ $item->id }}" selected>{{ $item->category_name }}</option>
                                @else  
                                    <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($events as $event)
                <tr>
                    <td>
                        <input id="ev_id[]" type="hidden" name="ev_id[]" value={{ $event->id }}>
                        <div class="col-300-auto"><a href="{{ url('/show-valeevent/'.$event->id) }}">
                        {{ $event->event_name }}
                        </a></div>
                    </td>
                    <td><div class="col-200-ellipsis-auto">{{ $event->description }}</div></td>
                   
                    <!--td>{-{ Carbon\Carbon::parse($event->event_date)->format('Y-m-d') }}</td-->
                    <td><div class="col-w70-auto">@if ($event->event_date != NULL){{ Carbon\Carbon::parse($event->event_date)->format('M Y') }}@endif</div></td> 
                    <!--td>{-{ strtok($event->event_date, '-') }}</td-->
                    <td>{{ $event->location_name }}</td>
                    <!--LUtd>{--{ $event->group_name }}</td-->
                    @if ($event->count_valID == 1 OR ($event->count_valID == NULL))
                        <td>{{ $event->first_name }} {{ $event->last_name }}</td>
                    @else
                        <!--td><a href="{-{ url('/edit-event/'.$event->id) }}" class="btn btn-sm btn-info">List subjects</a></td-->
                        <td>
                            @auth
                                @if (auth()->user() && isset(Auth::user()->id) && auth()->user()->hasVerifiedEmail())
                                    <div id="buttDiv" class="dropdown col-w120-auto" data-id={{ $event->id }}>
                                    <button id="valBtn{{ $event->id }}" value={{ $event->id }} class="dropbtn">List Names  ****</button>
                                        <div id="valeList_{{ $event->id }}" class="dropdown-content">
                                            <input hidden type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                                        </div>
                                    </div>
                                @endif
                            @else
                                <button disabled id="valBtn{{ $event->id }}" value={{ $event->id }} class="dropbtn">List Names  ****</button>
                            @endauth
                        </td>
                    @endif
                    <td>{{ $event->category_name }}</td>
                    <!--td>{-{ $event->count_valID }}</td-->
                    <td>
                        @auth
                            @if ((Auth::user()->id == $event->owner_id) || Auth::user()->id == 1)
                                <a href="{{ url('/edit-event/'.$event->id.'?page='.optional($events)->currentPage() ?? 1) }}" class="btn btn-sm btn-info">Edit</a>
                            @endif

                            @if (Auth::user()->id == 1)
                                <a href="{{ url('/destroy-event/'.$event->id.'?page='.optional($events)->currentPage() ?? 1) }}" class="btn btn-sm btn-danger">Delete</a>
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
@if(is_object($events) && method_exists($events, 'links'))
    <div id="pagination-links" class="paginate_div">{{ $events->links() }}</div>
@endif

<script src="{{ asset('js/list-data.js') }}"></script>
<script type="text/javascript">
    (function(){
        $('.dropbtn').on('click', function() {
            var eventID = $(this).val()
            //if (!confirm(`List them for "${eventID}". With mouse hover above button.`)) return;
            fetch_vale_data(eventID)
        });
    })();

    function filterFunction() {
        const input = document.getElementById("myInput");
        const filter = input.value.toUpperCase();
        const div = document.getElementById("valeList");
        const a = div.getElementsByTagName("a");
        for (let i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
            } else {
            a[i].style.display = "none";
            }
        }
    }

    $('#region_filter').on('change', function() {
        const regionSelected = $(this).val();
        const search_str = $('#search').val();
        const categoryID = $('#category_filter').val();
        const filterRoute = "{{ route('events.filter') }}";
        const urlRoute = "{{ url('/') }}" + "/region-ajax";
        
        fetch_select_cities(urlRoute, regionSelected, 'location_filter', null)
        get_data_output(filterRoute, null, categoryID, null, regionSelected, null, search_str, null)
    });
    
    $('#search_img').click(function() {
        const search_str = $('#search').val();
        const locationID = $('#location_filter').val();
        const categoryID = $('#category_filter').val();
        const regionSelected = $('#region_filter').val();
        const filterRoute = "{{ route('events.filter') }}";

        get_data_output(filterRoute, null, categoryID, null, regionSelected, locationID, search_str, null)
        $('#search').val(null);
    });

    $('#location_filter, #category_filter').on('change', function() {
        const search_str = $('#search').val();
        const locationID = $('#location_filter').val();
        const categoryID = $('#category_filter').val();
        const regionSelected = $('#region_filter').val();
        const filterRoute = "{{ route('events.filter') }}";

        get_data_output(filterRoute, null, categoryID, null, regionSelected, locationID, search_str, null)
    });

    function fetch_vale_data(eventID = null) {
        var SITEURL = "{{ url('/') }}";
        
        $.ajax({
            type:'POST',
            url: SITEURL + '/valeeventlist-ajax',
            data:{
                    event_ID:eventID,
                    type:'drop-box'
            },
            success:function(data){
                if(data){
                    toastr.success('List of Vale for Event # ' + eventID, 'Event');
                    $('#valeList_' + eventID + ' a').remove();
                    var html = '';
                    for (var key in data) {
                    if (data.hasOwnProperty(key)) {
                        html += '<a href="' + SITEURL + '/show/' + data[key].id + '" class="col-ellipsis-auto">' + data[key].first_name + '&nbsp;' + data[key].last_name + '</a>';
                    }
                    }
                    $('#valeList_' + eventID + ' input').first().after(html);
                }
            },
                error: function(xhr, status, error) {
                console.error('Error loading data:', error);
            }
        });
    }
</script>
</div>
</div>