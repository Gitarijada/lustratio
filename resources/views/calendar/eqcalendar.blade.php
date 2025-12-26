@extends('layouts.apppage')

@section('content')

    <!--link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" /-->
    <!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" /-->
    <!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" /-->
    
    {{-- Scripts --}}
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script-->
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script-->
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script-->
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script-->
    
    <link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet">
    <!--link href="{-{ asset('css/toastr.min.css') }}" rel="stylesheet"-->

    <!--script src="{-{ asset('js/jquery.min.js') }}"></script-->
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar.min.js') }}"></script>
    <!--script src="{-{ asset('js/toastr.min.js') }}"></script-->

    <div class="container mt-5" style="max-width: 900px">
        <h5 class="h5 text-center mb-5 border-bottom pb-3">Make The Event and Add Equipments</h5>
        <div id='full_calendar_events'></div>
    </div>

    <script>
        $(document).ready(function () {

            var SITEURL = "{{ url('/') }}";
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
          
            var calendar = $('#full_calendar_events').fullCalendar({
                editable: true,
                header:{
                    left:'prev, next today',
                    center:'title',
                    right:'month, agendaWeek, agendaDay'
                },
                
                defaultView: 'agendaWeek',  //default
                
                editable: true,
                events: SITEURL + "/calendar-event",
                //eventColor: '#00A4BD',
                //eventColor: '#378006',
                //textColor: 'yellow', // an option!
                //--moi1: #00A4BD;
                //contentHeight: 300,
                displayEventTime: true,
                editable: true,
                eventRender: function (event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;

                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,
                select: function (event_start, event_end, allDay) {
                    var event_name = prompt('Event Name:');
                   
                    if (event_name) {
                        var event_start = $.fullCalendar.formatDate(event_start, "Y-MM-DD HH:mm:ss");
                        var event_end = $.fullCalendar.formatDate(event_end, "Y-MM-DD HH:mm:ss");

                        $.ajax({
                            url: SITEURL + "/calendar-crud-ajax",
                            data: {
                                event_name: event_name,
                                event_start: event_start,
                                event_end: event_end,
                                type: 'create'
                            },
                            type: "POST",
                            success: function (data) {
                                displayMessage("Event created.");
                                calendar.fullCalendar('renderEvent', {
                                    id: data.id,
                                    title: event_name,
                                    start: event_start,
                                    end: event_end,
                                    //color: '#00A4BD',
                                    allDay: allDay
                                }, true);
                                calendar.fullCalendar('unselect');
                                window.location = SITEURL + "/create-equevent/" + data.id;
                            }
                        });
                    }
                },
                eventDrop: function (event, delta) {
                    var event_start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var event_end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");

                    $.ajax({
                        url: SITEURL + '/calendar-crud-ajax',
                        data: {
                            title: event.title,
                            start: event_start,
                            end: event_end,
                            id: event.id,
                            type: 'edit'
                        },
                        type: "POST",
                        success: function (response) {
                            displayMessage("Event updated");
                        }
                    });
                },
                eventResize:function(event, delta)
                {
                    var event_start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var event_end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");

                    $.ajax({
                        url: SITEURL + '/calendar-crud-ajax',
                            data:{
                                title: event.title,
                                start: event_start,
                                end: event_end,
                                id: event.id,
                                type: 'update'
                            },
                            type:"POST",
                            success:function(response)
                            {
                                calendar.fullCalendar('refetchEvents');
                                alert("Event Updated-Resized Successfully");
                                displayMessage("Event Resized Successfully" + event_name);
                            }
                    })
                },
                eventClick: function (event) {
  /*var out = '';
  for (var p in event) {
    out += p + ': ' + event[p] + '\n';
  }
  alert(out);*/
                    window.location = SITEURL + "/show-equevent/" + event.id;
                   
                }
            });
        });

        function displayMessage(message) {
            toastr.success(message, 'Event');            
        }

    </script>

@endsection