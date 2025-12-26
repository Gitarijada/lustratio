<div class="mb-3 item" data-help-title="Lokacija Help"
        data-help-text="Izaberite Region da bi ste suzili izbor gradova. Posle mozete izabrati Grad (regionalni centar). Nakon toga pruzice vam se opcija (novo polje) unosenja jos preciznije lokacije za dati Grad, ako je znate. U slucaju da je ostavite praznu, sistem ce uzeti regionalni centar-Grad kao lokaciju subjekta. Lokacija je obavezno polje i vazna je za citav sistem zbog preglednosti podataka i lakseg procesuiranja.">      
    <div id="div_select" style="border: 2px solid lightgray; padding: 8px 10px 8px;">
        <div style="display: flex;">
        <label>REGION&nbsp;&nbsp;&nbsp;</label>
        
        <select id="region_id2" name="region_id2"  class="form-control region-select">
            <option selected value="">Izaberi Region</option>
            @foreach($regions as $item)
                <option value="{{ $item->region }}">{{ $item->region }}</option>
            @endforeach
        </select>
        </div>

        <div style="display: flex;">
        <label>MESTO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                 
        <select id="location_id2" name="location_id2" class="form-control location-select">
            <option selected value="" data-region="" data-city_zip="" data-name="">Izaberi Grad*</option>
            @foreach($locations as $item)
                @if(isset($item_selected) && $item->id == $item_selected->location_id) 
                    <option value="{{$item->id}}" data-region="{{ $item->region }}" data-city_zip="{{ $item->city_zip }}" data-name="{{ $item->name }}" selected>{{ $item->name }}&nbsp;&nbsp;&nbsp;{{$item->zip}}</option>
                @else  
                    <option value="{{$item->id}}" data-region="{{ $item->region }}" data-city_zip="{{ $item->city_zip }}" data-name="{{ $item->name }}">{{ $item->name }}&nbsp;&nbsp;&nbsp;{{$item->zip}}</option>
                @endif
            @endforeach
        </select>
        </div>

        <div id="location_after2" style="display: none; margin: 7px 0px 0px 0px;">Please select Local, if you have the info
            <select id="local_id2" name="local_id2" class="form-control">
            </select>
        </div>
    </div>
</div>
