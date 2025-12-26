window.LocationSelector = (function () {

    function init(selectorPrefix = "") {
        const regionSelect     = document.querySelector(selectorPrefix + "#region_id2");
        const locationSelect   = document.querySelector(selectorPrefix + "#location_id2");
        const localSelect      = document.querySelector(selectorPrefix + "#local_id2");
        const localContainer   = document.querySelector(selectorPrefix + "#location_after2");

        // Keep all original <option> elements (including placeholder)
        const allLocationOptions = Array.from(locationSelect.options);

        if (!regionSelect || !locationSelect) return;
        
        // On region change
        regionSelect.addEventListener('change', function() {
            const selectedRegion = this.value.trim();

            if (localContainer.checkVisibility()) {
                localContainer.style.display = 'none';
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

            rebuildLocationOptions(allLocationOptions, locationSelect, filtered);
        });

        // On location change
        locationSelect.addEventListener('change', function() {
            const selectedCity = this.value.trim();

            if (!localContainer.checkVisibility()) {
                localContainer.style.display = 'block';
            }

            // Otherwise → filter by city
            const filtered = allLocationOptions.filter(opt => {
                return opt.dataset.city_zip === selectedCity;
            });

            rebuildLocal_Options(allLocationOptions, localSelect, filtered);
            
        });
    }

    // Helper: rebuild location select with given options
    function rebuildLocationOptions(allLocationOptions, locationSelect, filteredOptions) {
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
            //const city = option.dataset?.city ?? option.getAttribute('city');
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

    function rebuildLocal_Options(allLocationOptions, localSelect, filteredOptions) {
        localSelect.innerHTML = '';

        // Always add the placeholder first 'Izaberi grad'
        const placeholder = allLocationOptions.find(opt => opt.value === '');
        if (placeholder) {
            placeholder.textContent = `Izaberi Local`;
            localSelect.appendChild(placeholder.cloneNode(true));
        }

        filteredOptions.forEach(option => {
            const zip = option.value;
            const name = option.dataset?.name ?? option.getAttribute('data-name');

            if (option.value !== '' && zip) {
                //localSelect.appendChild(option.cloneNode(true));
                
                // Clone the option for zip-name order
                const newOpt = option.cloneNode(true);
                newOpt.textContent = `${zip}\u00A0\u00A0\u00A0${name}`;
                localSelect.appendChild(newOpt);
            }
        });

        // Reset selection
        localSelect.value = '';
    }

    return {
        init: init
    };

})();

// initialize on first load
document.addEventListener("DOMContentLoaded", () => {
    LocationSelector.init();
});
