<div class="mb-3 item" data-help-title="Ime Help"
                    data-help-text="Obavezan unos ovog polja">
    <label for="first_name">Ime <span class ="mandatory-star-label">*</span></label>
    <input id="first_name" name="first_name" type="text" class="form-control" placeholder="Enter the First Name">
</div>

<div class="mb-3 item" data-help-title="Prezime Help"
                    data-help-text="Obavezan unos ovog polja">
    <label for="last_name">Prezime <span class="mandatory-star-label">*</span></label>
    <input id="last_name" name="last_name" type="text" class="form-control" placeholder="Enter the Last Name">
</div>

<div class="mb-3">
    <label for="sobriquet">Nadimak</label>
    <input id="sobriquet" name="sobriquet" type="text" class="form-control" placeholder="Enter the Nadimak">
</div>

<div class="mb-3-date item" data-help-title="Datum Rodjenja Help"
                        data-help-text="Datum Rodjenja nije obavezan, ali je pozeljno je imati zbog slicnih imena. U sistemu prikazujemo samo Godinu Rodjenja, a celi datum nece biti nikome vidljiv.">
    <label for="date_of_birth">Datum Rodjenja</label>
    <input id="date_of_birth" name="date_of_birth" type="date" class="form-control" placeholder="Enter datum rodjenja">
</div>
                        
<div class="mb-3 item" data-help-title="Organizacija Help"
                    data-help-text="Dali je osoba pripadnik necega (stranke, organizacije...) Obavezno polje, po njemu mozemo grupisati subjekte.">
    <label for="party_id">Organizacija <span class="mandatory-star-label">*</span></label>
    <select id="party_id" name="party_id" class="form-control">
        <option value="">Izaberi Organizaciju</option>
        @foreach($parties as $party)
            <option value="{{$party->id}}">{{$party->name}}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="location_id">Lokacija <span class="mandatory-star-label">*</span></label>
    @include("location_list-template")
</div>
<div class="mb-3">
</div>

<div class="container-show">
    <div class="mb-3-60prc item" data-help-title="Zanimanje Help"
                        data-help-text="Zanimanje/profesija subjekta. Pocnite da kucate zanimanje u polje, pa izaberite nesto od ponudjenog ili unesite novo zanimanje ako ni jedno od ponudjenih ne odgovara.">
        <label for="occupation">Zanimanje</label>
        <input id="occupation" name="occupation" list="occ_suggestions" type="text" class="form-control" placeholder="Enter Profesija*">
        <datalist id="occ_suggestions">
            @foreach ($occupations as $option)
                <option value="{{ $option->occupation }}">{{ $option->occupation }}</option>
            @endforeach
        </datalist>
    </div>

    <div class="mb-3">
        <label for="position">Pozicija</label>
        <input id="position" name="position" type="text" class="form-control" placeholder="Enter the Pozicija">
    </div>
</div>

<div class="mb-3 item" data-help-title="Description Help" 
            data-help-text="Ako imate neke vazne podatke o samoj osobi. Podatke o dogadjaju ne unositi ovde. Polje nije obavezno.">
    <label for="val_description">Ako imate neke podatke o osobi koje zelite da unesete</label>
    <textarea id="val_description" name="val_description" cols="22" rows="3" class="form-control" maxlength="700" placeholder="Do 700 karaktera max">{{ old('val_description') }}</textarea>
    <!-- Progress Bar Container -->
    <!--div class="progress mt-2" style="height: 10px;">
        <div id="description-bar" 
             class="progress-bar bg-success" 
             role="progressbar" 
             style="width: 0%;" 
             aria-valuenow="0" 
             aria-valuemin="0" 
             aria-valuemax="700">
        </div>
    </div-->
    <small class="form-text text-muted">
        <span id="char-count">700</span> characters remaining.
        <!--span id="char-count">0</span> / 700 characters -->
    </small>
</div>

<div class="container-show">
    <div class="mb-3-half">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" class="form-control" placeholder="Enter Email">
    </div>

    <div class="mb-3-half">
        <label for="phone">Telefon</label>
        <input id="phone" name="phone" type="text" class="form-control" placeholder="Enter Telefon">
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Run the specific miscellaneous function for this page
        initCharCounter('val_description', 'char-count');
    });
</script>
@endpush
