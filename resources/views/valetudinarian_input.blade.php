<div class="mb-3 item" data-help-title="Ime Help"
                    data-help-text="Obavezan unos ovog polja">
    <label>Ime <span class ="mandatory-star-label">*</span></label>
    <input name="first_name" type="text" class="form-control" placeholder="Enter the First Name">
</div>

<div class="mb-3 item" data-help-title="Prezime Help"
                    data-help-text="Obavezan unos ovog polja">
    <label>Prezime <span class="mandatory-star-label">*</span></label>
    <input name="last_name" type="text" class="form-control" placeholder="Enter the Last Name">
</div>

<div class="mb-3">
    <label>Nadimak</label>
    <input name="sobriquet" type="text" class="form-control" placeholder="Enter the Nadimak">
</div>

<div class="mb-3-date item" data-help-title="Datum Rodjenja Help"
                        data-help-text="Datum Rodjenja nije obavezan, ali je pozeljno je imati zbog slicnih imena. U sistemu prikazujemo samo Godinu Rodjenja, a celi datum nece biti nikome vidljiv.">
    <label>Datum Rodjenja</label>
    <input name="date_of_birth" type="date" class="form-control" placeholder="Enter datum rodjenja">
</div>
                        
<div class="mb-3 item" data-help-title="Organizacija Help"
                    data-help-text="Dali je osoba pripadnik necega (stranke, organizacije...) Obavezno polje, po njemu mozemo grupisati subjekte.">
    <label>Organizacija <span class="mandatory-star-label">*</span></label>
    <select name="party_id" class="form-control">
        <option value="">Izaberi Organizaciju</option>
        @foreach($parties as $party)
            <option value="{{$party->id}}">{{$party->name}}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Lokacija <span class="mandatory-star-label">*</span></label>
    @include("location_list-template")
</div>
<div class="mb-3">
</div>

<div class="container-show">
    <div class="mb-3-60prc item" data-help-title="Zanimanje Help"
                        data-help-text="Zanimanje/profesija subjekta. Pocnite da kucate zanimanje u polje, pa izaberite nesto od ponudjenog ili unesite novo zanimanje ako ni jedno od ponudjenih ne odgovara.">
        <label>Zanimanje</label>
        <input name="occupation" list="occ_suggestions" type="text" class="form-control" placeholder="Enter Profesija*">
        <datalist id="occ_suggestions">
            @foreach ($occupations as $option)
                <option value="{{ $option->occupation }}">{{ $option->occupation }}</option>
            @endforeach
        </datalist>
    </div>

    <div class="mb-3">
        <label>Pozicija</label>
        <input name="position" type="text" class="form-control" placeholder="Enter the Pozicija">
    </div>
</div>

<div class="mb-3 item" data-help-title="Description Help" 
            data-help-text="Ako imate neke vazne podatke o samoj osobi. Podatke o dogadjaju ne unositi ovde. Polje nije obavezno.">
    <label>Ako imate neke podatke o osobi koje zelite da unesete</label>
    <textarea name="val_description" cols="22" rows="3" class="form-control" placeholder="Do 600 karaktera max"></textarea>
</div>

<div class="container-show">
    <div class="mb-3-half">
        <label>Email</label>
        <input name="email" type="email" class="form-control" placeholder="Enter Email">
    </div>

    <div class="mb-3-half">
        <label>Telefon</label>
        <input name="phone" type="text" class="form-control" placeholder="Enter Telefon">
    </div>
</div>