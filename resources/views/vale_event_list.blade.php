<div class="card mb-3">
  <img src="{{ asset('images/pobuna.jpeg') }}" class="w-2_12 mb-8 shadow-xl" alt="top_logo">
  <div class="card-body">
    <div class="list_container">
        <div class="fixed">
            <h5 class="card-title">List of VALE_EVENTS</h5>
            <p class="card-text">You will find here all the info about subjects in the system</p>
        </div>
        <div class="flex-item">
            <form action="{{ url('/crud-event-ajax') }}" method="GET">
                <input id="search" type="text" name="search" required/>
                <button id="search_btn" type="button">Search</button>
            </form>
        </div>    
    </div>
    <!--<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>-->
    <div class="col">
        <table id="thetable" class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Event</th>
                <th scope="col">Valetudinarian</th>
                <th scope="col">Start</th>
                <th scope="col">End</th>
            </tr>
        </thead>
        <tbody>
        @foreach($vale_events as $vale_event)
            <tr>
                <td>
                    <a href="{{ url('/show-valeevent/'.$vale_event->id) }}">
                    {{ $vale_event->id }}
                    </a>
                </td>
                <td>{{ $vale_event->event_id }}</td>
                <td>{{ $vale_event->valetudinarian_id }}</td>
                <!--LUtd>{--{ $vale_event->equ_start }}</td>
                <td>{--{ $vale_event->equ_end }}</td-->
                <td>
                    @auth
                        @if ((Auth::user()->id == $equ_event->owner_id) || Auth::user()->id == 1)
                            <a href="{{ url('/edit-equevent/'.$equ_event->event_id) }}" class="btn btn-sm btn-info">Edit</a>
                        @endif

                        @if (Auth::user()->id == 1)
                            <a href="{{ url('/destroy-equevent/'.$equ_event->event_id) }}" class="btn btn-sm btn-danger">Delete</a>
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

<!--DOESN'T WORK , to be fix, if decided-->
<!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script-->

<script type="text/javascript">
   
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   
    $('#search_btn').click(function() {
    //$('#ev_id').on('change', function() {
    
        //e.preventDefault();   // prevent message "fill the field"
        
        var SITEURL = "{{ url('/') }}";
        var search_str = $('#search').val();

        if (search_str == '') {
            displayMessage("Add Search Criteria, please"); 
            return; 
        }

        $('#search').val(null);

        $.ajax({
           type:'GET',
           url: SITEURL + '/crud-event-ajax',
           //url: SITEURL + '/ajaxRequest',
           //url:"{***{ route('ajaxRequest.post') }}",
           data:{
                //event_ID:eventID,
                search_Str:search_str,
                type:'search'
           },
           success:function(data){
                if(data){
                    displayMessage("Search For: " + search_str + "...");
                    $('#thetable tr').not(':first').remove();
                    //$('#thetable tr').not(':first').not(':last').remove();
                    var html = '';
                    var user_id = null;
                    for(var i = 0; i < data.length; i++) {

                        if (Number.isInteger(data[i])) {
                            user_id = data[i];
                            continue;
                        }
                        
                            html += '<tr><td><a href="{{ url("/show/' + data[i].id + '") }}">' + data[i].event_name + '</a>' + 
                                    '</td><td>' + data[i].event_start + 
                                    '</td><td>' + data[i].event_end + 
                                    '</td><td>' + data[i].created_at + 
                                    '</td><td>' + data[i].update_at;

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

                                    /*'</td><td> @if (Auth::user())' + 
                                    '<a href="{{ url("/edit/' + data[i].id + '") }}" class="btn btn-sm btn-info">Edit</a>' + 
                                    '<a href="{{ url("/destroy/' + data[i].id + '") }}" class="btn btn-sm btn-danger">Delete</a>' + 
                                    '@else <p>Please login, in order to edit items.</p> @endif' + 
                                    '</td></tr>';*/
                    }
                    $('#thetable tr').first().after(html);
                }else{
                    $('#search').val('');
                }
           }
           error: function(xhr, status, error) {
            console.error('Error fetching user info:', error);
           }
        });
  
    });

    function displayMessage(message) {
        toastr.success(message, 'Event');            
    }

</script>



