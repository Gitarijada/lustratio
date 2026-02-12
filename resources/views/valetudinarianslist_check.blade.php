<div id="data-output-append">
<div id="data-output">

<div class="card mb-3">
    <img src="{{ asset('images/pobuna.jpeg') }}" class="w-2_12 mb-8 shadow-xl" alt="top_logo">
    <div class="card-body">
        <div class="list_container">
            <div class="fixed">
                <h5 class="card-title">List of Valetudinarians</h5>
                <p class="card-text">You will find here all the info about subjects in the system</p>
            </div>
            <div class="flex-item">
                <input id="search" type="text" name="search">      <!--required/-->
                <img id="search_img" src="{{ asset('images/search-icon-no-background-hd-260.png') }}" alt="Search" width=3% height=auto />
            </div>    
        </div>
    <table id="thetable" class="table">
        <thead>
            <tr>
                @if($layout != 'show')
                    <th scope="col">Select</th>
                @endif
                <th scope="col" class="col-200">Name</th>
                <th scope="col">G. Rođ.</th>
                @if($layout == 'show')      {{-- OR $layout == createfromvaleid --}}
                    <th scope="col">Organizacija</th>
                    <th scope="col">Mesto</th>
                @else
                <th scope="col">
                    <select id="party_filter" name="party_filter" class="form-control">
                        <option value="">Izaberi Pripadnost</option>
                        @foreach($parties as $item)  
                            @if(isset($drop_item_selected) && $item->id == $drop_item_selected->get('party_ID'))  
                                <option value="{{$item->id}}" selected>{{$item->name}}</option>
                            @else  
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </th>
                <th scope="col">
                    <div id="div_select" style="border: 2px solid lightgray;">
                        <select id="region_filter" name="region_filter" class="form-control">
                            <option selected value="">Izaberi Region</option>
                            @foreach($regions as $item)
                                @if(isset($drop_item_selected) && $item->region == $drop_item_selected->get('region')) 
                                    <option value="{{ $item->region }}" selected>{{ $item->region }}</option>
                                @else  
                                    <option value="{{ $item->region }}">{{ $item->region }}</option>
                                @endif
                            @endforeach
                        </select>
                                        
                        <select id="location_filter" name="location_filter" class="form-control">
                            <option selected value="">Izaberi Grad</option>
                            @foreach($cities as $item)
                                @if(isset($drop_item_selected) && $item->city_zip == $drop_item_selected->get('location_ID')) 
                                    <option value="{{ $item->city_zip }}" selected>{{ $item->city }}</option>
                                @else  
                                    <option value="{{ $item->city_zip }}">{{ $item->city }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </th>
                @endif

                @if($layout != 'show')
                    <th scope="col">At Disposition</th>
                    <th scope="col"></th>
                @endif

            </tr>
        </thead>
        <tbody>
        @foreach($valetudinarians as $valetudinarian)
            <tr>
                @if($layout != 'show')
                    <td><input type="checkbox" @if(isset($valetudinarian->vale_selected) && $valetudinarian->vale_selected == 1) checked @endif @if($valetudinarian->used_by_other == 1) disabled @endif name="vale_selected[]" value="{{ $valetudinarian->id }}"></td>
                @endif
                <td class="text-nowrap">
                    <a href="{{ url('/show/'.$valetudinarian->id) }}">{{ $valetudinarian->first_name }} {{ $valetudinarian->last_name }}</a>
                </td>
                <td>{{ strtok($valetudinarian->date_of_birth, '-') }}</td>
                <td>{{ $valetudinarian->party_name }}</td>
                <td>{{ $valetudinarian->location_name }}</td>

                @if($layout != 'show')  
                    @if($valetudinarian->used_by_other == 0)
                        <td><img src="{{ asset('images/gui_check_yes_icon_157194.png') }}" alt="True" width=12% height=auto /></td>                               
                    @else
                        <td><img src="{{ asset('images/gui_check_no_icon_157196.png') }}" alt="False" width=12% height=auto /></td>
                    @endif

                    <td>
                        @auth
                            @if ((Auth::user()->id == $valetudinarian->owner_id) || Auth::user()->id == 1)
                                <a href="{{ url('/edit/'.$valetudinarian->id.'?page='.optional($valetudinarians)->currentPage() ?? 1) }}" class="btn btn-sm btn-info">Edit</a>
                            @endif

                            @if (Auth::user()->id == 1)
                                <a href="{{ url('/destroy/'.$valetudinarian->id.'?page='.optional($valetudinarians)->currentPage() ?? 1) }}" class="btn btn-sm btn-danger">Delete</a>
                            @endif
                        @endauth
                    </td>
                @endif
                                
            @isset($valetudinarian->vev_description)
            <tr>
                <th>Event Description for {{ $valetudinarian->first_name }} {{ $valetudinarian->last_name }}
                    @auth
                        @if ((Auth::user()->id == $valetudinarian->owner_id) || Auth::user()->id == 1)
                            <br>
                            <button id="button_edit-{{ $valetudinarian->id }}" type="button" 
                                    class="btn btn-sm btn-info mt-2 edit-btn" 
                                    onclick="toggleEdit(this, {{ $valetudinarian->id }}, {{ $event->id }})">
                                Edit
                            </button>
                            <button id="button_cancel-{{ $valetudinarian->id }}" type="button" 
                                    class="btn btn-sm btn-warning mt-2 cancel-btn" 
                                    onclick="toggleCancel(this, {{ $valetudinarian->id }})"
                                    style="display: none;">
                                Cancel
                            </button>
                        @endif
                    @endauth
                </th>
                <td colspan="3">
                    <div class="mb-3">
                        <div id="vev_desc-{{ $valetudinarian->id }}" class="fake-textarea">{!! $valetudinarian->formatted_vev_description !!}</div>
                        {{-- textarea have maxlength and is used mainly foe input, div is use for output because formated (here we use it vo output and for editing (input)) --}}
                        {{--<textarea id="vev_desc-{{ $valetudinarian->id }}" class="form-control vevtextarea" maxlength="800">{!! $valetudinarian->formatted_vev_description !!}</textarea>--}}
                        <div id="progress-{{ $valetudinarian->id }}" style="display: none;">
                            <div class="progress mt-2" style="height: 10px;">
                                <div id="description-bar-{{ $valetudinarian->id }}" 
                                    class="progress-bar bg-success" 
                                    role="progressbar" 
                                    style="width: 0%;" 
                                    aria-valuenow="0" 
                                    aria-valuemin="0" 
                                    aria-valuemax="800">
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                <!--span id="vev_char-count">800</span> characters remaining. -->
                                <!--span id="vev_char-count">0</span> / 800 characters -->
                                <span id="vev_char-count-{{ $valetudinarian->id }}">0</span> / 800 characters
                            </small>
                        </div>
                    </div>
                </td>
            </tr>
            @endisset
            
        @endforeach
        </tbody>
    </table>
    </div>

</div>
@if(is_object($valetudinarians) && method_exists($valetudinarians, 'links'))
    <div id="pagination-links" class="paginate_div">{{ $valetudinarians->links() }}</div>
@endif

<script src="{{ asset('js/list-data.js') }}"></script>

<script type="text/javascript">
function toggleEdit(button, valetudinarian_id, event_id) {
    const elementarea = document.getElementById(`vev_desc-${valetudinarian_id}`);
    const isEditing = elementarea.contentEditable === "true";
    const divProgress = document.getElementById(`progress-${valetudinarian_id}`);
    const buttonCancel = document.getElementById(`button_cancel-${valetudinarian_id}`);

    if (!isEditing) {

        //elementarea.setAttribute('data-original-content', elementarea.innerHTML); // // innerText not give html links, new text ONLY for DIV
        //elementarea.setAttribute('data-original-content', elementarea.value); // Get the new text ONLY TEXTAREA
        // Save current content before editing starts ---
        if (elementarea.tagName === 'TEXTAREA' || elementarea.tagName === 'INPUT') {
            elementarea.setAttribute('data-original-content', elementarea.value);
        } else {
            elementarea.setAttribute('data-original-content', elementarea.innerHTML);
        }

        // Switch to EDIT mode
        elementarea.contentEditable = "true";
        elementarea.style.backgroundColor = "#fff"; // White background when editing
        elementarea.style.border = "2px solid #17a2b8"; // Highlight border
        //elementarea.style.height = "150px";      // Forces the height to your max-height limit
        //elementarea.style.overflowY = "auto";    // Adds a scrollbar if text exceeds 150px
        elementarea.focus();
        button.innerText = "Save";
        button.classList.replace("btn-info", "btn-success");
        //$('#button_cancel').show();
        buttonCancel.style.display = "inline-block";
        divProgress.style.display = "inline-block";
    } else {
        // Switch to SAVE mode
        //const updatedContent = elementarea.innerText; // innerText not give html links
        //const updatedContent = elementarea.value; 
        var updatedContent = null;
        if (elementarea.tagName === 'TEXTAREA' || elementarea.tagName === 'INPUT') {
            updatedContent = elementarea.value;
        } else {
            updatedContent = elementarea.innerText; //not innerHTML (only output), because we save as a plain text (innerText - only input)
        }

        // --- AJAX save call here ---
        update_vev_desc(valetudinarian_id, event_id, updatedContent)

        // Reset UI
        elementarea.contentEditable = "false";
        elementarea.style.backgroundColor = "#e9ecef";   //"#f9f9f9";
        elementarea.style.border = "1px solid #ced4da";  //#dee2e6";
        //elementarea.style.height = "auto";      // Let it shrink back if text is small
        //elementarea.style.overflowY = "";       // Remove forced scrollbar
        divProgress.style.display = "none";
        button.innerText = "Edit";
        button.classList.replace("btn-success", "btn-info");
        buttonCancel.style.display = "none";
    }
}

function toggleCancel(button, valetudinarian_id) {
    const buttonEdit = document.getElementById(`button_edit-${valetudinarian_id}`);
    const elementarea = document.getElementById(`vev_desc-${valetudinarian_id}`);
    const divProgress = document.getElementById(`progress-${valetudinarian_id}`);
    
    // --- Revert to the original saved content ---
    const originalContent = elementarea.getAttribute('data-original-content');
    
    if (originalContent !== null) {
        if (elementarea.tagName === 'TEXTAREA' || elementarea.tagName === 'INPUT') {
            elementarea.value = originalContent;
        } else {
            //elementarea.innerText = originalContent;  // innerText not give html links
            elementarea.innerHTML = originalContent;
        }
    }

    elementarea.contentEditable = "false";
    elementarea.style.backgroundColor = "#e9ecef";   //"#f9f9f9";
    elementarea.style.border = "1px solid #ced4da";  //#dee2e6";
    //elementarea.style.height = "auto";      // Let it shrink back if text is small
    //elementarea.style.overflowY = "";       // Remove forced scrollbar
    divProgress.style.display = "none";
    buttonEdit.innerText = "Edit";
    buttonEdit.classList.replace("btn-success", "btn-info");
    button.style.display = "none";
}

function update_vev_desc(valetudinarianID = null, eventID = null, vevDESC = null) {
    const SITEURL = "{{ url('/') }}";
    //alert($('meta[name="csrf-token"]').attr('content'));
    $.ajax({
        //_token: "{{ csrf_token() }}",
        url: SITEURL + '/update-valeevent',
        type: 'POST',
        data: {
            valetudinarian_id: valetudinarianID,
            event_id: eventID,
            vev_description: vevDESC
            //_token: $('meta[name="csrf-token"]').attr('content') // Laravel security
        },
        success: function(response) {
            console.log("Record found:", response);
        },
        error: function(xhr) {
            console.error("Error executing query", xhr.statusText);
        }
    });
}
</script>

<script type="text/javascript">
    $('#region_filter').on('change', function() {
        const regionSelected = $(this).val();
        const eventID = $('#ev_id').val();
        const search_str = $('#search').val();
        const partyID = $('#party_filter').val();
        const checkedCheckboxes = document.querySelectorAll('input[name="vale_selected[]"]:checked');
        const vale_selected = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);
        const filterRoute = "{{ route('vale.filter') }}";
        const urlRoute = "{{ url('/') }}" + "/region-ajax";
        
        fetch_select_cities(urlRoute, regionSelected, 'location_filter', null)
        get_data_output(filterRoute, eventID, null, partyID, regionSelected, null, search_str, vale_selected)
    });

    $('#party_filter, #location_filter').on('change', function() {
        const search_str = $('#search').val();
        const eventID = $('#ev_id').val();
        const partyID = $('#party_filter').val();
        const locationID = $('#location_filter').val();
        const regionSelected = $('#region_filter').val();
        const checkedCheckboxes = document.querySelectorAll('input[name="vale_selected[]"]:checked');
        // 2. Convert the NodeList to an array and map their values
        const vale_selected = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);
        const filterRoute = "{{ route('vale.filter') }}";

        if (eventID == null) { 
            toastr.success("An event is not chosen", 'Event'); 
        }

        get_data_output(filterRoute, eventID, null, partyID, regionSelected, locationID, search_str, vale_selected)
    });
    
    $('#search_img').click(function() {
        const search_str = $('#search').val();
        const eventID = $('#ev_id').val();
        const partyID = $('#party_filter').val();
        const locationID = $('#location_filter').val();
        const regionSelected = $('#region_filter').val();
        const checkedCheckboxes = document.querySelectorAll('input[name="vale_selected[]"]:checked');
        const vale_selected = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);
        const filterRoute = "{{ route('vale.filter') }}";

        if (eventID == null) { 
            toastr.success("An event is not chosen", 'Event'); 
        } 
        
        get_data_output(filterRoute, eventID, null, partyID, regionSelected, locationID, search_str, vale_selected)
        $('#search').val(null);
    });

</script>
</div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // We loop through all elements starting with 'vev_desc-'
        const editableDivs = document.querySelectorAll('[id^="vev_desc-"]');
        
        editableDivs.forEach(div => {
            // Extract the ID from the element ID (e.g., 'vev_desc-15' -> '15')
            const val_id = div.id.split('-')[1];

            // Initialize the counter for this specific row
            initProgressBarCounter(
                `vev_desc-${val_id}`, 
                `vev_char-count-${val_id}`, 
                `description-bar-${val_id}`
            );
        });
    });
</script>
@endpush
{{-- similar solution within child Modal window after confirmation "selection-event.js"
setTimeout(() => {   
                            if (typeof initProgressBarCounter === 'function') {
                                initProgressBarCounter('vev_description', 'vev_char-count', 'description-bar');
                            }
                        }, 0); //}, 50);

-- "vale_event.blade" inside of function get_data_confirmation(eventID = null, valeSelected = null) {}
as a part of const confirmBtn = document.getElementById('confirmBtn');
    if (confirmBtn) {}
setTimeout(() => {
            // Find all textareas in the newly appended content
            const textareas = document.querySelectorAll('.vev-textarea');
                            
            textareas.forEach(textarea => {
                // Extract the numeric ID from our element ID (e.g., "vev_desc__45" -> "45")
                const id = textarea.id.replace('vev_desc_', '');
                                
                // Initialize the counter for this specific row
                if (typeof initCharCounter === 'function') {
                    initCharCounter(`vev_desc_${id}`, `vev_char-count_${id}`);
                    //initProgressBarCounter('vev_desc_${id}', 'vev_char-count_${id}', 'description-bar_${id}');
                }
            });

            var confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            confirmModal.show();
        }, 0);     // }, 50);  was 50 milliseconds--}}






