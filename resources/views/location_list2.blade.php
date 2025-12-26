<div class="mb-3 item" data-help-title="Lokacija Help"
        data-help-text="Izaberite Region da bi ste suzili izbor gradova. Posle mozete izabrati Grad (regionalni centar). Nakon toga pruzice vam se opcija (novo polje) unosenja jos preciznije lokacije za dati Grad, ako je znate. U slucaju da je ostavite praznu, sistem ce uzeti regionalni centar-Grad kao lokaciju subjekta. Lokacija je obavezno polje i vazna je za citav sistem zbog preglednosti podataka i lakseg procesuiranja.">      
    <div id="div_select" style="border: 2px solid lightgray; padding: 8px 10px 8px;">
        <div style="display: flex;">
        <label>REGION&nbsp;&nbsp;&nbsp;</label>
        
        <select id="region_id2" name="region_id2" class="form-control">
            <option selected value="">Izaberi Region</option>
            @foreach($regions as $item)
                <option value="{{ $item->region }}">{{ $item->region }}</option>
            @endforeach
        </select>
        </div>

        <div style="display: flex;">
        <label>MESTO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> 
                 
        <select id="location_id2" name="location_id2" class="form-control">
            <option selected value="" data-city="">Izaberi Grad</option>
            @foreach($locations as $item)
                @if(isset($item_selected) && $item->id == $item_selected->location_id) 
                    <option value="{{$item->id}}" data-city="{{ strtolower($item->name) }}" selected>{{ $item->zip }}&nbsp;&nbsp;&nbsp;{{$item->name}}</option>
                @else  
                    <option value="{{$item->id}}" data-city="{{ strtolower($item->name) }}">{{ $item->zip }}&nbsp;&nbsp;&nbsp;{{$item->name}}</option>
                @endif
            @endforeach
        </select>
        </div>
    
        <div id="location_after2" style="display: none; margin: 7px 0px 0px 0px;">Please select Local, if you have the info</div>
    </div>
</div>

<script type="text/javascript">
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    //var target_element2 = 'location_id2';

    $('#region_id2').on('change', function() {
        var region = $(this).val();
        //displayMessage("Search For: " + region + "...");
        //var region = $('#region_filter').val(); 
		fetch_select_cities_2(region, 'location_id2', 'Region') 
    });

    $('#location_id2').on('change', function() {
        var locationID = $('#location_id2').val();
        //var locationID = $(this).val();
        //displayMessage("Search For: " + locationID + "...");
        /*const divElement = document.getElementById("location_after2");
        if (divElement.checkVisibility()) {
            //$('#' + 'local_id2').remove();
        } else {
            //fetch_select_data2_2(locationID, 'local_id2', 'City')
        }*/
        fetch_select_data2_2(locationID, 'local_id2', 'City')   
    });

//***************************** select option letter search *** /
document.addEventListener('DOMContentLoaded', function () {
  const select = document.getElementById('location_id2');
  let searchBuffer = '';
  let timer = null;

  /*select.addEventListener('mousedown', function(e) {  //not to open pop down <options>, for search 'keydown'
    e.preventDefault(); // prevent dropdown open on click
    this.focus();       // keep focus for typing
  });*/
  select.addEventListener('keydown', function (e) {
    //if (e.key.length === 1 && /^[a-zA-ZčćšđžČĆŠĐŽ]$/.test(e.key)) {
    if (e.key.length === 1 && /^[a-zA-ZčćšđžČĆŠĐŽ ]$/.test(e.key)) {
      searchBuffer += e.key.toLowerCase();
      clearTimeout(timer);

      timer = setTimeout(() => searchBuffer = '', 1200); // reset after 1.2s

      let foundIndex = -1;

      for (let i = 0; i < select.options.length; i++) {
        const option = select.options[i];
        if (option.dataset.city.startsWith(searchBuffer)) {
          select.selectedIndex = i;
          foundIndex = i;
          break;
        }
      }

      // ✅ Scroll smoothly to the matched option if found
      if (foundIndex !== -1) {
        // Wait for DOM update before scrolling
        setTimeout(() => {
          const option = select.options[foundIndex];
          option.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 50);
      }

      e.preventDefault();
    } 
    else if (e.key === 'Backspace') {
      searchBuffer = searchBuffer.slice(0, -1);
      e.preventDefault();
    }
  });
});
//************************* select option letter search End *** /

    function fetch_select_cities_2(valueID = null, element = null, message = null) {
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
                    const divElement = document.getElementById("location_after2");
                    if (divElement.checkVisibility()) {
                        $('#local_id2').remove();
                        divElement.style.display = 'none';
                        //$('#local_id2 option').not(':first').remove();
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

    function fetch_select_data2_2(valueID = null, element = null, message = null) {
        var SITEURL = "{{ url('/') }}";
        
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

                    const divElement = document.getElementById("location_after2");
                    if (divElement.checkVisibility()) {
                        $('#local_id2').remove();
                            //$('#' + 'local_id2 option').not(':first').remove();
                            //$('#' + 'local_id2 option').remove();
                            //$('#local_id2').empty();
                            //divElement.innerHTML = '';
                            //divElement.options.length = 0;
                    }
                    
                    var html = '<select id="local_id2" name="local_id2" class="form-control">';
                    html += '<option selected value="">Izaberi Local</option>';   //this stay if not(':first')
                    for(var i = 0; i < data.length; i++) {
                        html += '<option value="' + data[i].id + '">' + data[i].zip + '&nbsp;&nbsp;&nbsp;' + data[i].name + '</option>'; 
                    }
                    $('#' + 'location_after2').first().after(html);
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


    
