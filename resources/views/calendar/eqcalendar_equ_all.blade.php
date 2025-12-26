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
        <h2 class="h2 text-center mb-5 border-bottom pb-3">All Equipment's Renting Plan</h2>
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
   
                editable: true,
                events: SITEURL + "/calendar-equ",
                //eventColor: '#378006',
                //contentHeight: 300,
                displayEventTime: true,
                editable: true,
                eventRender: function (event, element, view) {
                    if (event.id % 9 == 0) {
                        //event.color = "#FFB347"; //Em andamento
                        element.css('background-color', '#FFB347');
                    } else if (event.id % 8 == 0) {
                        //event.color = "#77DD77"; //Concluído OK
                        element.css('background-color', '#77DD77');
                    } else if (event.id % 7 == 0) {
                        //event.color = "#AEC6CF"; //Não iniciado
                        element.css('background-color', '#AEC6CF');
                    } else if (event.id % 6 == 0) {
                        //event.color = "#77DD77"; //Concluído OK
                        element.css('background-color', '#3490dc');
                    } else if (event.id % 5 == 0) {
                        //event.color = "#77DD77"; //Concluído OK
                        element.css('background-color', '#6574cd');
                    } else if (event.id % 4 == 0) {
                        //event.color = "#77DD77"; //Concluído OK
                        element.css('background-color', '#9561e2');
                    } else if (event.id % 3 == 0) {
                        //event.color = "#77DD77"; //Concluído OK
                        element.css('background-color', '#f66d9b');
                    } else if (event.id % 2 == 0) {
                        //event.color = "#77DD77"; //Concluído OK
                        element.css('background-color', '#e3342f');
                    }
                   
                    if (event.allDay === 'true') {
                        event.allDay = true;

                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,
                
                eventClick: function (event) {
  /*var out = '';
  for (var p in event) {
    out += p + ': ' + event[p] + '\n';
  }
  alert(out);*/
                    //window.location = SITEURL + "/show/" + event.id;    //event.id is id_equ (id equipment)
                    var eventDelete = confirm("Delete from Event.\nAre you sure?");
                    if (eventDelete) {
                        $.ajax({
                            type: "POST",
                            url: SITEURL + '/calendar-crud-ajax',
                            data: {
                                id: event.id,
                                type: 'delete-equ'
                            },
                            success: function (response) {
                                calendar.fullCalendar('removeEvents', event.id);
                                displayMessage("Equipment removed from Event");
                            }
                        });
                    }
                }
            });
        });

        function displayMessage(message) {
            toastr.success(message, 'Event');            
        }

    </script>

@endsection