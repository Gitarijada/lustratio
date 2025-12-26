$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    
function get_data_output(filterRoute, eventID = null, categoryID = null, partyID = null, regionSelected = null, locationID = null, search_str = null, valeSelected = null) {
        //const SITEURL = "{{ url('/') }}";
        $.ajax({
            type:'POST',
            //url: SITEURL + '/output-ajax',
            //url:"{{ route('vale.filter') }}",
            url:filterRoute,
            data:{
                //_token: "{{ csrf_token() }}",
                event_ID:eventID,
                category_ID:categoryID,
                party_ID:partyID,
                region:regionSelected,
                location_ID:locationID,
                search_Str:search_str,
                vale_selected:valeSelected,
                type:'data-output'
            },
            success:function(data){
                var message = '';
                if(data){
                    if (regionSelected) message = `Region ${regionSelected}`;
                    if (locationID) message += `City ${locationID}`;
                    if (partyID) message += `Organisation ${partyID}`;
                    if (search_str) message += `Search... ${search_str}`;
                    if(message.length !== 0) toastr.info(message, 'Criteria...');
              
                    $('#data-output').remove();
                    //$('#pagination-links').remove();
                    $('#data-output-append').append(data.html);
                                                                        /*const endTime = Date.now();
                                                                        const elapsedTime = endTime - startTime;
                                                                        alert('JSON ' + partyID + ' ' + elapsedTime);*/
                }else{
                    toastr.info('Oops Something went wrong" error on processing no matter ...', 'Loading');
                }
            },
                error: function(xhr, status, error) {
                console.error('Error loading data:', error);
            }
        });
}

function fetch_select_cities(filterRoute, valueID = null, element = null, message = null) {
        //const SITEURL = "{{ url('/') }}";
        $.ajax({
            type:'POST',
            //url: SITEURL + '/region-ajax',
            url:filterRoute,
            data:{
                    value_ID:valueID,
                    type:'select-region-list'
            },
            success:function(data){
                if(data){
                    if(message){
                        toastr.info(message + ' ' + valueID + ' selected', 'Criteria...');
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
                    toastr.info(message + " went wrong !!!", 'Criteria...');
                }
            },
                error: function(xhr, status, error) {
                console.error('Error loading data:', error);
            }
        });
}