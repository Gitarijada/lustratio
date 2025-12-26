window.LocationSelector = (function () {

    function init(selectorPrefix = "") {
        const regionSelect     = document.querySelector(selectorPrefix + "#region_id");
        const locationSelect   = document.querySelector(selectorPrefix + "#location_id");
        const localSelect      = document.querySelector(selectorPrefix + "#local_id");
        const localContainer   = document.querySelector(selectorPrefix + "#location_after");

        if (!regionSelect || !locationSelect) return;

        const allLocationOptions = Array.from(locationSelect.options);

        regionSelect.addEventListener("change", function () {
            //handleRegionChange(this.value.trim(), locationSelect, allLocationOptions);
            handleRegionChange(this.value, locationSelect, allLocationOptions);
            hideLocal(localContainer);
        });

        locationSelect.addEventListener("change", function () {
            handleLocationChange(this.value, localSelect, allLocationOptions);
            showLocal(localContainer);
        });

        //user can choose to attach vale to an existing Event insted to create new one.
        const existingEventBtn = document.querySelector(selectorPrefix + "#existingEventBtn");
        existingEventBtn.addEventListener('click', () => {
            if (!confirm(`"Event". You ading a new Event and ya'll add the following persons to that new existing event.`)) return;
            const type = existingEventBtn.getAttribute('data-mode');
            //const type = existingEventBtn.dataset.mode;
            //const type = 'data-event-main';
            
            const filterRoute = "{{ route('vale.event-input') }}";
            get_data_event(filterRoute, null, type)
        });
    }

    function handleRegionChange(region, locationSelect, allOptions) {
        locationSelect.innerHTML = "";

        if (!region) {
            allOptions.forEach(opt => locationSelect.appendChild(opt.cloneNode(true)));
            return;
        }

        const placeholder = allOptions.find(opt => opt.value === "");
        if (placeholder) locationSelect.appendChild(placeholder.cloneNode(true));

        const filtered = allOptions.filter(opt => opt.dataset.region === region);

        const addedZips = new Set();

        filtered.forEach(opt => {
            const zip = opt.value;
            /*const city_zip = option.dataset?.city_zip ?? option.getAttribute('data-city_zip');
            const city = option.dataset?.city ?? option.getAttribute('city');
            const name = option.dataset?.name ?? option.getAttribute('data-name');

            if (option.value !== '' && city_zip && zip === city_zip && !addedZips.has(city_zip)) {
                addedZips.add(city_zip);
                const newOpt = option.cloneNode(true);

                newOpt.textContent = `${zip}\u00A0\u00A0\u00A0${name}`;
                locationSelect.appendChild(newOpt);
            }*/

            const city_zip  = opt.dataset.city_zip;
            const name = opt.dataset.name;

            if (!zip || zip !== city_zip || addedZips.has(city_zip)) return;

            const copy = opt.cloneNode(true);
            copy.textContent = `${zip}\u00A0\u00A0\u00A0${name}`;
            addedZips.add(city_zip);

            locationSelect.appendChild(copy);
        });

        locationSelect.value = "";
    }

    function handleLocationChange(city_zip, localSelect, allOptions) {
        localSelect.innerHTML = "";

        const placeholder = document.createElement("option");
        placeholder.value = "";
        placeholder.textContent = "Izaberi Local";
        localSelect.appendChild(placeholder);

        const filtered = allOptions.filter(opt => opt.dataset.city_zip === city_zip);

        filtered.forEach(opt => {
            const zip = opt.value;
            const name = opt.dataset.name;
            const copy = opt.cloneNode(true);
            copy.textContent = `${zip}\u00A0\u00A0\u00A0${name}`;
            localSelect.appendChild(copy);
        });

        localSelect.value = "";
    }

    function showLocal(el) {
        if (el) el.style.display = "block";
    }

    function hideLocal(el) {
        if (el) el.style.display = "none";
    }

    return {
        init: init
    };

})();

// initialize on first load
document.addEventListener("DOMContentLoaded", () => {
    LocationSelector.init();
});
