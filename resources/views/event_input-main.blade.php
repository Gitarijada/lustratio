<div id="data-event-main-append">
<div id="data-event-main">

    <div class="mb-3 item" data-help-title="KATEGORIJA Help"
        data-help-text="This box represents the first category. It may contain photos, files, or text data associated with section one.">
        <div class="category-container-button">
            <label>KATEGORIJA <span class="mandatory-star-label">*</span></label>
            @if($layout === 'make_famous' || $layout === 'create_vale_event' || $layout === 'create/att_vale_event')
                <a id="existingEventBtn" data-mode="data-event-main" class="btn btn-sm btn-outline-primary toolbar-btn">Choose Among Existing Events</a>
            @elseif($layout === 'choose')
                <a id="existingEventBtn" data-mode="data-event-combined" class="btn btn-sm btn-outline-primary toolbar-btn">New Event</a>
            @endif
        </div>
            <div class="mb-3">
                <select id="category_id" name="category_id" class="form-control">
                    @if($layout != 'edit' || (isset($item_selected) && $item_selected->category_id == null))
                        <option selected value=''>Izaberi Kategoriju - if applicable</option>
                    @endif 
                    @foreach($categories as $item)
                        @if(isset($item_selected) && ($item->id == $item_selected->category_id))  
                            <option value="{{$item->id}}" selected>{{$item->category_name}}</option>
                        @endif 
                        <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                    @endforeach
                </select>
            </div>    
    </div>
    <div class="dropdown-divider"></div>
    <div class="mb-3 item" data-help-title="Event Name Help"
        data-help-text="This box represents the first category. It may contain photos, files, or text data associated with section one.">
        <label>Event Name <span class="mandatory-star-label">*</span></label>
        @if($layout != 'add' && $layout != 'choose')
            <input @if($layout == 'edit') value="{{ $item_selected->event_name }}"@endif id="event_name" name="event_name" type="text" class="form-control" placeholder="Enter the Event Name*">
        @else
            <div id="div_select" class="mb-3">
                <select id="ev_id" name="event_id" class="form-control input-frame">
                    <option selected disabled hidden style='display: none' value=''>Choose Event*</option>
                    @foreach($events as $item)
                        @if(isset($drop_item_selected) && $item->id == $drop_item_selected->get('event_ID')) 
                            <option value="{{ $item->id }}" selected>{{ $item->event_name }}</option>
                        @else  
                            <option value="{{ $item->id }}">{{ $item->event_name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        @endif 
    </div>
    <div class="mb-3">
        @if($layout != 'add' && $layout != 'choose')<label>Lokacija <span class="mandatory-star-label">&nbsp;&nbsp;ako je lokacija dogadjaja na vise teritorija ne unosi se ili unesi "SRBIJA"</span></label>
        @else<label>Lokacija <span class="mandatory-star-label">*</span></label>
        @endif
            @if($layout == 'make_famous' || $layout == 'create_vale_event' || $layout == 'choose')  {{-- where  gonna appear as a second location --}}
                    <!--@-include("location_list2")-->
                @include("location_list-template2")
            @else
                    <!--@-include("location_list")-->
                @include("location_list-template")
            @endif
    </div>
                
</div>
</div>

{{--@push('scripts')
<script>
    // WAS for "#existingEventBtn"
</script>
@endpush--}}