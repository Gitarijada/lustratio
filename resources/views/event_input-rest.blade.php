<div id="data-event-rest-append">
<div id="data-event-rest">
    @if($layout != 'add')        <!--for "add" we don't need them--> <!--ADDING VALETUDINARIAN TO EVENT (For: Create, Edit)-->
        <div class="mb-3-date-full item" data-help-title="Date Help"
        data-help-text="Datum Dogadjaja nije obavezan, ali ako postoji, pozeljno je uneti bar orijentacijoni datum. Neki dogadjaji su trajali duze vremena, ali pokusajte uneto bar orijentacioni pocetek dogadjaja, a u opisu mozete navesti celo trajanje.">
            @if($layout != 'show_&vev_desc' && $layout != 'show')<label>Datum<span class="mandatory-star-label">&nbsp;&nbsp;ako se događaj Eventa odvija neprestalno, ne unosi se. U slucaju izabranog "Mesec" uneti prvi u mesecu/godini.</span></label>
            @else<label>Datum</span></label>
            @endif
            @if($layout == 'show')
                <input disabled @if(isset($item_selected->event_date))value="{{ Carbon\Carbon::parse($item_selected->event_date)->format($item->precision_date ?? 'd M Y') }}"@endif>
            @else
                <div class="container-date">
                <input @if($layout == 'edit') value="{{ $item_selected->event_date }}"@endif name="event_date" type='date' class="form-control" placeholder="Enter Date*">  
                    <div class="form-check form-check-inline me-3"> <!-- Added me-3 class -->
                        <input type="radio" name="precision_date" value="d M Y" class="form-check-input" id="radioDay"
                            {{ (old('precision_date', $layout == 'edit' ? $item_selected->precision_date : 'd M Y') == 'd M Y') ? 'checked' : '' }}>
                        <label class="form-check-label" for="radioDay">Datum</label>
                    </div>
                    <div class="form-check form-check-inline me-3"> <!-- Added me-3 class -->
                        <input type="radio" name="precision_date" value="M Y" class="form-check-input" id="radioMonth"
                            {{ (old('precision_date', $layout == 'edit' ? $item_selected->precision_date : '') == 'M Y') ? 'checked' : '' }}>
                        <label class="form-check-label" for="radioMonth">Mesec/Godina</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="precision_date" value="Y" class="form-check-input" id="radioYear"
                            {{ (old('precision_date', $layout == 'edit' ? $item_selected->precision_date : '') == 'Y') ? 'checked' : '' }}>
                        <label class="form-check-label" for="radioYear">Godina</label>
                    </div>
                </div>
            @endif
        </div>    

        <div class="mb-3 item" data-help-title="Description Help"
        data-help-text="Ovde unosite opis dogadjaja. Mozete uneti i linkove na stranice, kao i copy/paste text iz drugih izvora. Pokusajte da jasno unesete sve podatke vezane za dogadaj koji se tice subjekta za koji je vezan.">
            <label>Description <span class ="mandatory-star-label">*</span></label>
            @if($layout == 'show' || $layout == 'show_&vev_desc')
                <textarea id="description" disabled name="description" cols="40" rows="5" class="form-control" placeholder="Enter Description">{{ $item_selected->description }}</textarea>
            @elseif($layout == 'edit')
                <textarea id="description" name="description" cols="40" rows="8" class="form-control" placeholder="Enter Description">{{ $item_selected->description }}</textarea>
            @else
                <textarea id="description" name="description" cols="40" rows="8" class="form-control" @if($layout == 'make_famous')placeholder="Type Description HERE (Tekst ispod je samo 'Privremeni tekst') !!!&#10;{{ $image_guess->description }}"
                @else placeholder="Enter Description"@endif></textarea>
            @endif
        </div>
        @if($layout == 'show_&vev_desc' || $layout == 'create_vale_event')  {{-- we don't want confusion of two new description for make-famous/no 'layout'=='make_famous' --}}
            <div class="mb-3 item" data-help-title="Dodatni opis dogadjaja u vezi date osobe Help"
                data-help-text="Ovde mozete uneti dodatni opis dogadjaja vezanih za osobu koju trenutno unosite. Opis date osobe u svetlu izabranog dogadjaja. Mozete uneti i linkove na stranice, kao i copy/paste text iz drugih izvora. Pokusajte da jasno unesete sve podatke vezane za dogadaj koji se tice subjekta za koji je vezan.">
                <textarea id="vev_description" name="vev_description" cols="40" rows="5" class="form-control" maxlength="800" placeholder="Enter additional Event Description for {{ isset($valetudinarian->first_name, $valetudinarian->last_name) ? $valetudinarian->first_name . ' ' . $valetudinarian->last_name : 'this particular subject' }}"></textarea>
                {{-- WAS: placeholder="Enter additional Event Description for this particular subject"--}}
                    <!-- Progress Bar Container -->
                    <div class="progress mt-2" style="height: 10px;">
                        <div id="description-bar" 
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
                    <span id="vev_char-count">0</span> / 800 characters
                </small>
            </div>
        @endif

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

{{-- because we have it at choose-newSelector as ChooseNewSelector.init(); and also as later as we drawing event_input-rest ChooseNewSelector.init(); in selection=event-js 
@push('rest-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Run the specific miscellaneous function for this page
        initProgressBarCounter('vev_description', 'vev_char-count', 'description-bar');
    });
</script>
@endpush--}}