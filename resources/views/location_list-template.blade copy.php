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
                    <option value="{{$item->id}}" data-region="{{ $item->region }}" data-city_zip="{{ $item->city_zip }}" data-name="{{ $item->name }}" selected>{{ $item->name }}&nbsp;&nbsp;&nbsp;{{$item->zip}}</option>
                @else  
                    <option value="{{$item->id}}" data-region="{{ $item->region }}" data-city_zip="{{ $item->city_zip }}" data-name="{{ $item->name }}">{{ $item->name }}&nbsp;&nbsp;&nbsp;{{$item->zip}}</option>
                @endif
            @endforeach
        </select>
        </div>

        <div id="location_after" style="display: none; margin: 7px 0px 0px 0px;">Please select Local, if you have the info
            <select id="local_id" name="local_id" class="form-control">
            </select>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const regionSelect = document.getElementById('region_id');
    const locationSelect = document.getElementById('location_id');

    const divElement = document.getElementById("location_after");
    const local_Select = document.getElementById('local_id');

    // Keep all original <option> elements (including placeholder)
    const allLocationOptions = Array.from(locationSelect.options);

    // Helper: rebuild location select with given options
    function rebuildLocationOptions(filteredOptions) {
        locationSelect.innerHTML = '';

        // Always add the placeholder first 'Izaberi grad'
        const placeholder = allLocationOptions.find(opt => opt.value === '');
        if (placeholder) {
            locationSelect.appendChild(placeholder.cloneNode(true));
        }

        // Add filtered unique options
        const addedZips = new Set();
        filteredOptions.forEach(option => {
            //const city_zip = option.dataset.city_zip;
            const zip = option.value;
            const city_zip = option.dataset?.city_zip ?? option.getAttribute('data-city_zip');
            const city = option.dataset?.city ?? option.getAttribute('city');
            //const region = option.dataset?.region ?? option.getAttribute('data-region');
            const name = option.dataset?.name ?? option.getAttribute('data-name');

            if (option.value !== '' && city_zip && zip === city_zip && !addedZips.has(city_zip)) {
                addedZips.add(city_zip);
                
                //locationSelect.appendChild(option.cloneNode(true));
                
                // Clone the option
                const newOpt = option.cloneNode(true);
                // Replace inner text to show ZIP first, then city name
                // We normalize spaces and entities
                //const displayText = `${city_zip}    ${option.textContent.replace(/\s+/g, ' ').trim().split(/\s/)[0]}`;
                //newOpt.textContent = displayText;
                newOpt.textContent = `${zip}\u00A0\u00A0\u00A0${name}`;
                locationSelect.appendChild(newOpt);

            }
        });

        // Reset selection
        locationSelect.value = '';
    }

    function rebuildLocal_Options(filteredOptions) {
        local_Select.innerHTML = '';

        // Always add the placeholder first 'Izaberi grad'
        const placeholder = allLocationOptions.find(opt => opt.value === '');
        if (placeholder) {
            placeholder.textContent = `Izaberi Local`;
            local_Select.appendChild(placeholder.cloneNode(true));
        }

        filteredOptions.forEach(option => {
            const zip = option.value;
            const name = option.dataset?.name ?? option.getAttribute('data-name');

            if (option.value !== '' && zip) {
                //local_Select.appendChild(option.cloneNode(true));
                
                // Clone the option for zip-name order
                const newOpt = option.cloneNode(true);
                newOpt.textContent = `${zip}\u00A0\u00A0\u00A0${name}`;
                local_Select.appendChild(newOpt);
            }
        });

        // Reset selection
        local_Select.value = '';
    }

    // On region change
    regionSelect.addEventListener('change', function() {
        const selectedRegion = this.value.trim();

        if (divElement.checkVisibility()) {
            divElement.style.display = 'none';
        }

        // If no region selected → restore all (unique) locations
        if (!selectedRegion) {
            //rebuildLocationOptions(allLocationOptions);   not because rebuildLocationOptions filltering just city_zip
            locationSelect.innerHTML = '';
            allLocationOptions.forEach(option => {
                locationSelect.appendChild(option.cloneNode(true));
            });
            return;
        }

        // Otherwise → filter by region and keep unique city_zip
        const filtered = allLocationOptions.filter(opt => {
            return opt.dataset.region === selectedRegion;
        });

        rebuildLocationOptions(filtered);
    });

    // On location change
    locationSelect.addEventListener('change', function() {
        const selectedCity = this.value.trim();

        if (!divElement.checkVisibility()) {
            divElement.style.display = 'block';
        }

        // Otherwise → filter by city
        const filtered = allLocationOptions.filter(opt => {
            return opt.dataset.city_zip === selectedCity;
        });

        rebuildLocal_Options(filtered);
        
    });

});
</script>
