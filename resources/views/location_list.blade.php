<div class="mb-3 item" data-help-title="Lokacija Help"
        data-help-text="Izaberite Region da bi ste suzili izbor gradova. Posle mozete izabrati Grad (regionalni centar). Nakon toga pruzice vam se opcija (novo polje) unosenja jos preciznije lokacije za dati Grad, ako je znate. U slucaju da je ostavite praznu, sistem ce uzeti regionalni centar-Grad kao lokaciju subjekta. Lokacija je obavezno polje i vazna je za citav sistem zbog preglednosti podataka i lakseg procesuiranja.">      
    <div id="div_select" style="border: 2px solid lightgray; padding: 8px 10px 8px;">
        <div style="display: flex;">
        <label>REGION&nbsp;&nbsp;&nbsp;</label>
        
        <select id="region_id" name="region_id"  class="form-control region-select">
            <option selected value="">Izaberi Region</option>
            @foreach($regions as $item)
                <option value="{{ $item->region }}">{{ $item->region }}</option>
            @endforeach
        </select>
        </div>

        <div style="display: flex;">
        <label>MESTO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> 
                 
        <select id="location_id" name="location_id" class="form-control location-select">
            <option selected value="" data-region="" data-city_zip="" data-name="">Izaberi Grad</option>
            @foreach($locations as $item)
                @if(isset($item_selected) && $item->id == $item_selected->location_id) 
                    <option value="{{$item->id}}" data-region="{{ $item->region }}" data-city_zip="{{ $item->city_zip }}" data-name="{{ strtolower($item->name) }}" selected>{{ $item->name }}&nbsp;&nbsp;&nbsp;{{$item->zip}}</option>
                @else  
                    <option value="{{$item->id}}" data-region="{{ $item->region }}" data-city_zip="{{ $item->city_zip }}" data-name="{{ strtolower($item->name) }}">{{ $item->name }}&nbsp;&nbsp;&nbsp;{{$item->zip}}</option>
                @endif
            @endforeach
        </select>
        </div>

        <div id="location_after" style="display: none; margin: 7px 0px 0px 0px;">Please select Local, if you have the info</div>
    </div>
</div>

<script type="text/javascript">

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    //const target_element = 'location_id';

    $('#region_id').on('change', function() {
        const region = $(this).val();
        //displayMessage("Search For: " + region + "...");
        //var region = $('#region_filter').val(); 
		fetch_select_cities(region, 'location_id', 'Region') 
    });

    $('#location_id').on('change', function() {
        const locationID = $('#location_id').val();
        //var locationID = $(this).val();
        //displayMessage("Search For: " + locationID + "...");
        /*const divElement = document.getElementById("location_after");
        if (divElement.checkVisibility()) {
            //$('#' + 'local_id').remove();
        } else {
            //fetch_select_data2(locationID, 'local_id', 'City')
        }*/
        fetch_select_data2(locationID, 'local_id', 'City')   
    });

    function fetch_select_cities(valueID = null, element = null, message = null) {
        const SITEURL = "{{ url('/') }}";
        
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
                    const divElement = document.getElementById("location_after");
                    if (divElement.checkVisibility()) {
                        $('#local_id').remove();
                        divElement.style.display = 'none';
                        //$('#' + 'local_id option').not(':first').remove();
                    }
                    $('#' + element + ' option').not(':first').remove();
                    //$('#location_filter option').not(':first').remove();
                    //$('#thetable tr').not(':first').not(':last').remove();
                    var html = '';
                    //var html = '<option selected disabled hidden style="display: none" value="">Choose Event</option>';   //this stay if not(':first')
                    for(var i = 0; i < data.length; i++) {
                        html += '<option value="' + data[i].id + '">' + data[i].zip + '&nbsp;&nbsp;&nbsp;' + data[i].name + '</option>'; 
                    }
                    $('#' + element + ' option').first().after(html);
                    //$('#location_filter option').first().after(html);
                }else{
                    displayMessage(message + " went wrong !!!");
                }
           },
                error: function(xhr, status, error) {
                console.error('Error loading location list:', error);
           }
        });
    }

    function fetch_select_data2(valueID = null, element = null, message = null) {
        const SITEURL = "{{ url('/') }}";
        
        $.ajax({
           type:'POST',
           url: SITEURL + '/region-ajax',
           data:{
                value_ID:valueID,
                type:'select-local-list'
           },
           success:function(data){
                if(data){
                    if(message){
                        displayMessage(message + ' ' + valueID + ' selected');
                    }

                    const divElement = document.getElementById("location_after");
                    if (divElement.checkVisibility()) {
                        $('#' + 'local_id').remove();
                            //$('#' + 'local_id option').not(':first').remove();
                            //$('#' + 'local_id option').remove();
                            //$('#local_id').empty();
                            //divElement.innerHTML = '';
                            //divElement.options.length = 0;
                    }
                    
                    var html = '<select id="local_id" name="local_id" class="form-control">';
                    html += '<option selected value="">Izaberi Local</option>';   //this stay if not(':first')
                    for(var i = 0; i < data.length; i++) {
                        html += '<option value="' + data[i].id + '">' + data[i].zip + '&nbsp;&nbsp;&nbsp;' + data[i].name + '</option>'; 
                    }
                    $('#location_after').first().after(html);
                    divElement.style.display = 'block'; 

                }else{
                    displayMessage(message + " went wrong !!!");
                }
           },
                error: function(xhr, status, error) {
                console.error('Error loading location list:', error);
           }
        });
    }

    function displayMessage(message) {
        toastr.success(message, 'Event');            
    }

</script>


    
