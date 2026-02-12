$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    
function fetch_select_events(filterRoute, categoryID = null, region = null, locationID = null, message = null) {
        //const SITEURL = "{{ url('/') }}";
        $.ajax({
            type:'POST',
            //url: SITEURL + '/valeevent-ajax',
            url:filterRoute,
            data:{
                //_token: "{{ csrf_token() }}",
                category_ID:categoryID,
                region_NAME:region,
                location_ID:locationID,
                type:'select-option-list'
            },
            success:function(data){
                if(data){
                    if(message){
                        toastr.success(message, 'Event');
                    }
                    if(locationID){
                        toastr.info(`Svi DOGADJAJI za dati ${region}\u00A0\u00A0 ${locationID}, i kao i oni bez poznate lokacije`, 'Event');
                    }
                    $('#ev_id option').not(':first').remove();
                    var html = '<option selected value="">Choose Event</option>';   //this stay if not(':first')
                    for(var i = 0; i < data.length; i++) {
                            html += '<option value="' + data[i].id + '">' + data[i].event_name + '</option>'; 
                    }
                    $('#ev_id option').first().after(html);
                }else{
                    toastr.info(message + " went wrong !!!", 'Event');
                }
            },
                error: function(xhr, status, error) {
                console.error('Error loading data:', error);
            }
        });
}

function get_data_event(filterRoute, eventID = null, type = 'data-event-rest', layout = 'show') {
        //const SITEURL = "{{ url('/') }}";
        //alert($('meta[name="csrf-token"]').attr('content'));
        $.ajax({
            type:'POST',
            //url: SITEURL + '/exist-event-input',
            //url:"{{ route('vale.event-input') }}",
            url:filterRoute,
            data:{
                //_token: "{{ csrf_token() }}",
                event_ID:eventID,
                type:type,
                layout:layout
            },
            success:function(data){
                if(data){
                    const data_main = document.getElementById('data-event-main');
                    const data_rest = document.getElementById('data-event-rest');
                    if(type == 'data-event-main') {
                        if (data_main) {
                            data_main.remove();

                            if (data_rest) {
                                data_rest.remove();
                            }
                           
                            $('#' + type + '-append').append(data.html);
                            // IMPORTANT — reinitialize after injecting the HTML
                            existingEventOnChange.init_onChange();
                            ChooseNewSelector.init();
                            LocationSelector.init();
                            HelpModal.init();
                            toastr.success('Choose existing Event, select CATEGORY OR LOCATION to search Event.', 'Event...');
                        }
                    } else if(type == 'data-event-rest') {
                        const categoryID = document.getElementById('category_id');
                        const locationID = document.getElementById('location_id');
                        const locationID2 = document.getElementById('location_id2');
                     
                        categoryID.value = data.item_selected.category_id;
                        if (locationID2) locationID2.value = data.item_selected.location_id;
                        else if (locationID) locationID.value = data.item_selected.location_id;
                        
                        if (data_rest) {
                            data_rest.remove();
                        }

                        $('#' + type + '-append').append(data.html);
                        HelpModal.init();
                        toastr.success('Event chosen. Detailes are...', 'Event...');
                        // 2. RE-INITIALIZE the specific scripts for the new elements
                        // These functions must be globally accessible (defined in your miscellaneous.js)
                        // to inject HTML via AJAX, the browser needs a few milliseconds to process the new elements and update the DOM tree.
                        // The 50 represents 50 milliseconds (which is 0.05 seconds) MDN Web Docs - setTimeout.
                        setTimeout(() => {   
                            if (typeof initProgressBarCounter === 'function') {
                                initProgressBarCounter('vev_description', 'vev_char-count', 'description-bar');
                            }
                        }, 0); //}, 50);
                    } else if(type == 'data-event-combined') {
                        data_main.remove();
                        if (data_rest) {
                            data_rest.remove();
                        }
                        $('#data-event-main-append').html(data.html_main);
                        $('#data-event-rest-append').html(data.html_rest);
                        ChooseNewSelector.init();
                        LocationSelector.init();
                        HelpModal.init();
                        toastr.success('Create New Event !!!', 'Event...');
                    }
                                                                        /*const endTime = Date.now();
                                                                        const elapsedTime = endTime - startTime;
                                                                        alert('JSON ' + partyID + ' ' + elapsedTime);*/
                }else{
                    toastr.info('Oops Something went wrong" error on processing no matter ...', 'Loading');
                }
            },
                error: function(xhr, status, error) {
                console.error("Error loading data:", status, error);
                //console.log("Response Text:", xhr.responseText);
            }
        });
}

