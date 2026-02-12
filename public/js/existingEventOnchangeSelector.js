const existingEventOnChange = {
    //siteurl: null,

    /*init(selectorPrefix = "") {
    },*/

    init_onChange() {
        // cache DOM only once
        //this.siteurl = window.siteConfig.baseUrl;

        $('#data-event-main-append').on('change', '#category_id', function () {
            const categorySelect = document.getElementById('category_id').value;
            const regionSelect = document.getElementById('region_id2').value;
            var locationID = document.getElementById('location_id2').value;
            const local_Select = document.getElementById('local_id2').value;
            //const urlRoute = "{{ url('/') }}" + "/valeevent-ajax"; 
            const urlRoute = BASE_URL + "/valeevent-ajax";    
            if (local_Select.length !== 0) locationID = local_Select;
            if (locationID.length === 0) toastr.info("LOKACIJA nije izabrana za selekciju DOGADJAJA.", 'Event'); 
    //alert('MCat '+categorySelect+' reg-> '+regionSelect+' L-> '+locationID+' local-> '+local_Select);
            fetch_select_events(urlRoute, categorySelect, regionSelect, locationID, 'KATEGORIJA izabrana za Event selekciju')
            document.getElementById('data-event-rest')?.remove();   //modern syntax. Reset/remove bottom part of event input 
            //Reset the select menu to the selected default value option. Here is region_id2, not region_id like in vale_event.blade
            //$('#region_id2').val('').trigger('change.select2');   //commented because it not doing reset of selection of location_id2, but it has to.
            //$('#location_id2').val('').trigger('change.select2');   // Trigger change so Select2 updates the visual box
        });

        $('#data-event-main-append').on('change', '#region_id2, #location_id2', function() {     //not in use #location_id is not on the page. Maybe to expand later
            const categorySelect = document.getElementById('category_id').value;
            const regionSelect = document.getElementById('region_id2').value;
            var locationID = document.getElementById('location_id2').value;
            const local_Select = document.getElementById('local_id2').value;
            //const urlRoute = "{{ url('/') }}" + "/valeevent-ajax";
            const urlRoute = BASE_URL + "/valeevent-ajax";
        
            var message = 'LOKACIJA izabrana za selekciju DOGADJAJA.';
            if (local_Select.length !== 0) {
                locationID = local_Select;
            } else if (regionSelect.length !== 0) message = 'REGION izabran. Izaberi GRAD za selekciju DOGADJAJA.';
            if (categorySelect.length === 0) toastr.info("KATEGORIJA nije izabrana za selekciju DOGADJAJA.", 'Event'); 

            fetch_select_events(urlRoute, categorySelect, regionSelect, locationID, message)
            document.getElementById('data-event-rest')?.remove();   //modern syntax. Reset/remove bottom part of event input 
        });

        $('#data-event-main-append').on('change', '#ev_id', function () {
            eventID = $('#ev_id').val();
            /*if (eventID == '') { 
                toastr.info("You must have an event", 'Event');
                return; 
            }*/

            const id_header = document.querySelector("#new_val_event_hx");
            var layout = undefined;
            if (id_header) {
                layout = 'show_&vev_desc';
            }
            //const filterRoute = "{{ route('vale.event-input') }}";
            const urlRoute = BASE_URL + "/exist-event-input";
            get_data_event(urlRoute, eventID, undefined, layout)    //get_data_event(filterRoute, eventID, type = 'data-event-rest', 'show_&vev_desc') 'show_&vev_desc' mean show val_event_desc listbox
        });
    },

};

// initialize on first load
//document.addEventListener('DOMContentLoaded', () => existingEventOnChange.init());