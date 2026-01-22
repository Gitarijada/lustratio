<div id="data-event-rest-append">
<div id="data-event-rest">

    @if($layout != 'add')        <!--for "add" we don't need them--> <!--ADDING VALETUDINARIAN TO EVENT (For: Create, Edit)-->
        <div class="mb-3-date item" data-help-title="Date Help"
        data-help-text="Datum Dogadjaja nije obavezan, ali ako postoji, pozeljno je uneti bar orijentacijoni datum. Neki dogadjaji su trajali duze vremena, ali pokusajte uneto bar orijentacioni pocetek dogadjaja, a u opisu mozete navesti celo trajanje.">
            <label>Date</label>
            @if($layout == 'show')
                <input disabled @if(isset($item_selected->event_date))value="{{ Carbon\Carbon::parse($item_selected->event_date)->format('M Y') }}"@endif>
            @else
                <input @if($layout == 'edit') value="{{ $item_selected->event_date }}"@endif name="event_date" type='date' class="form-control" placeholder="Enter Date*">
            @endif
        </div>    

        <div class="mb-3 item" data-help-title="Description Help"
        data-help-text="Ovde unosite opis dogadjaja. Mozete uneti i linkove na stranice, kao i copy/paste text iz drugih izvora. Pokusajte da jasno unesete sve podatke vezane za dogadaj koji se tice subjekta za koji je vezan. It may contain photos, files, or text data associated with section one.">
            <label>Description <span class ="mandatory-star-label">*</span></label>
            @if($layout == 'show')
                <textarea id="description" disabled name="description" cols="40" rows="5" class="form-control" placeholder="Enter Description">{{ $item_selected->description }}</textarea>
            @elseif($layout == 'edit')
                <textarea id="description" name="description" cols="40" rows="8" class="form-control" placeholder="Enter Description">{{ $item_selected->description }}</textarea>
            @else
                <textarea id="description" name="description" cols="40" rows="8" class="form-control" @if($layout == 'make_famous')placeholder="{{ $image_guess->description }}"
                @else placeholder="Enter Description"@endif></textarea>
            @endif
        </div>

        @if($layout == 'edit')
            <div class="mb-3">
                <a href="{{ url('/upload_event_img/'.$item_selected->id) }}">Upload Photo for: {{ $item_selected->event_name }}</a>
            </div>
        @elseif($layout != 'show')
            <div class="mb-3 item" data-help-title="Upload Photo Help"
                data-help-text="This box represents the first category. It may contain photos, files, or text data associated with section one.">
                <label>Upload Event's Photo</label><br>
                @if($layout == 'make_famous' || $layout == 'create_vale_event')
                    <input type="file" name="image2" id="image2">
                @else
                    <input type="file" name="image" id="image">
                @endif
            </div>
        @endif 
    @endif
                
</div>
</div>
                        
 