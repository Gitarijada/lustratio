window.ChooseNewSelector = (function () {

    function init(selectorPrefix = "") {
        //user can choose to attach vale to an existing Event insted to create new one.
        const existingEventBtn = document.querySelector(selectorPrefix + "#existingEventBtn");

        if (existingEventBtn) {
            existingEventBtn.addEventListener('click', () => {
                if (!confirm(`"Event" You adding a Event and ya'll add the following persons to that new existing event.`)) return;
                const type = existingEventBtn.getAttribute('data-mode');
                //const type = existingEventBtn.dataset.mode;
                //const type = 'data-event-main';
                const id_header = document.querySelector("#new_val_event_hx");
                var layout = undefined;
                if (id_header) {
                    layout = 'create_vale_event';
                } else {
                    layout = 'create/att_vale_event';
                }
                
                const filterRoute = BASE_URL + "/exist-event-input";   //const filterRoute = "{{ route('vale.event-input') }}";
                get_data_event(filterRoute, null, type, layout)
            });
        }

        const existingVev_description = document.querySelector(selectorPrefix + "#vev_description");

        if (existingVev_description) {
            existingVev_description.addEventListener('click', () => {
                // Run the specific miscellaneous function for this page
                initProgressBarCounter('vev_description', 'vev_char-count', 'description-bar');
            });
        }
    }

    return {
        init: init
    };

})();

// initialize on first load
document.addEventListener("DOMContentLoaded", () => {
    ChooseNewSelector.init();
});
