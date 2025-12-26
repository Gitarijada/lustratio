@extends('layouts.apppage')

@section('content')
    <style>
        /* Navigation arrows */
        #viewerModal .start-0 {
            left: 0 !important;
        }
        #viewerModal .end-0 {
            right: 0 !important;
        }
        #viewerModal .nav-arrow-container {
            top: 50%;
            transform: translateY(-50%);
        }
        #viewerModal .nav-arrow {
        display: inline-block;        /* ensures transform/line-height behave */
        font-size: 25px !important;   /* explicit size (try 12px, 10px, 14px) */
        line-height: 1 !important;    /* avoid extra vertical stretching */
        padding: 0.25rem 1.0rem;      /* keep a tappable area */
        cursor: pointer;
        user-select: none;
        }
            /*div.position-absolute {
                outline: 1px solid red; 
            }*/ /* visual debug borders */
        /* Navigation arrows End*/

        .toast-info {
            background-color:#2F96B4;   /*overwrite toastr.info color*/
        }

        body { background: #f7f7fb; }
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 16px;
            padding: 24px;
        }
        .thumb {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            cursor: pointer;
            background: #fff;
        }
        .thumb img {
            width: 100%;
            height: 60px;
            object-fit: cover;
            display: block;
        }
        .thumb .name {
            padding: 6px 8px;
            font-size: 12px;
            color: #444;
            text-align: center;
            background: #fff;
        }

        /* Modal custom toolbar styles */
        /*.viewer-img { max-height: 70vh; max-width: 40%; display:block; margin: 0 auto; }*/
        .viewer-img { max-height: 30vh; max-width: 100%; display:block; margin: 0 auto; }
        .toolbar-btn { margin-right: 8px; }
        .nav-arrow { font-size: 2rem; cursor: pointer; user-select: none; }
    </style>

    <div class="row header-container justify-content-center">
        <div class="header">
            <h2>SLUCAJEVI</h2>
        </div>
    </div>

    <div class="container-fluid mt-4">
        <div class="justify-content-center">
            <div class="header text-center">
                <h4><br>Ucinimo ih Poznatim Galerija</h4>
            </div>
                                                    <!-- dd(Auth::user()) }}--> 
                                                    <!--{-{ dd($photos) }} -->
            <div class="gallery" id="gallery">
                <!--@ forelse($photos as $image_name => $id)-->
                @forelse($photos as $photo)
                    <div class="thumb" data-filename="{{ $photo->image_name }}" data-id="{{ $photo->id }}" data-help-text="{{ $photo->description }}">
                        <img src="{{ asset('storage/guess_images/' . $photo->image_name) }}" alt="{{ $photo->id }}">
                        <!--div class="name">{-{ $p }}</div-->
                    </div>
                @empty
                    <p class="text-center text-muted">No photos found in the folder.</p>
                @endforelse
            </div>
        </div>
        <div class="row justify-content-center">
            <section class="col-md-9">
                @include("valetudinarianslist")
            </section>
        </div>
    </div>   

<!-- Shared modal -->
<div id="helpModal" class="help-modal">
    <button class="help-close" id="closeHelp">&times;</button>
    <div class="help-title" id="helpTitle"></div>
    <div class="help-body" id="helpText"></div>
</div>

<!-- Viewer Modal (Bootstrap) -->
<div class="modal fade" id="viewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-content">
                @if(session('showMainInfoModal'))
                <div id="viewerModal" class="modal-body text-center position-relative"> <!-- btn was class="btn btn-sm btn-info" -->
                    <div class="header text-center">
                        <h5><strong>Da ih Ne Zaboravimo</strong></h5>
                    </div>
                    <p>Ова власт мора да оде. Да би Србија преживела. И да за генерације које долазе знамо тачно по имену ко су били ти људи који су зарад свог џепа и личних интереса замало уништили Србију.
                        Da upamtimo ko je sta radio, podrzavao. Da ne bude <strong>'Ja sam samo radio svoj posao'</strong> Bez njihovih pomoci ova vlast nebi opstala. I zato je vazno da znamo ko su, gde su.
                        Oni vecinom znaji da to sto rade nije dobro, ali ipak rade. Ili su ucenjeni, ili su njihovi, ili rade iz koristi. Kako god, kriju se i nezele da se zna da to rade.
                        E, zelimo da budu svi na jednom mestu i da svi mogu da vide ko su, mozda prestanu to da rade. Sve ih mocemo naci na 
                        <a href="{{ url('/equ/') }}" class="btn btn-sm btn-outline-primary toolbar-btn">Slucajevi</a>
                        A sve dogadjaje u koje su umesani su 
                    <a href="{{ url('/list-event/') }}" class="btn btn-sm btn-outline-primary toolbar-btn">Events'</a>
                        Mozete da ih dodajete, kao i dogadjaje/radnje u koje su umesani. Mozete i da dodajete osobe umesane u razlicite dogadjaje koji su vec u sistemu.
                        Kao i da kreirate dogadjeje za vec unesene osobe.</p>
                    <p>Mozete i postavljati slike osoba koji su ucestvovali u odredjenim radnjama/dogadjajima i postavljati ih u rublici <strong>’Učinimo ih poznatim’</strong>
                    <a href="{{ url('/upload_guess_img/') }}" class="btn btn-sm btn-outline-danger toolbar-btn">Dodaj novog 'Učinimo ih poznatim'</a>
                        Posle ko prepozna osobu na fotgrafiji moze da unese u sistem, zajedno sa ostalim dostupnim informacijama.</p> 
                    <p>Podatci koje unesete su u pocetnom neproverenom nivou. Sistem poseduje algoritam provere, gde podatci mogu preci u veci nivo pouzdanosti.
                        Zbog toga je vazno da unosite sto preciznije podatke. Kao i vase podatke gde mozemo dati kredibilitet napisanom, a tako prepoznati zlonamerne unose i unose os strane botova.</p>
                    <p>Mozete pretrazivati osobe po pripadnosti, lokaciji... <strong>’Neka ostanu upamceni’</strong>
                    </p>
                    <div class="header text-center">
                        <h6>Vazno je - Da ih ne zaboravimo - Da znamo ko su - Da ostanu upamceni</h6>
                        <h5><strong>"Svak je rođen da po jednom umre, čast i bruka žive dovijeka"</strong></h5>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-body text-center position-relative item" id="modal-cnt" data-help-title="Dogadjaj u vezi osobe na fotografiji" data-help-text="">
                <!--style="background-image: url('{-{ asset('images/eve_bckg3.jpg') }}'); background-size: cover; background-position: center;"-->
                <div class="modal-body text-center position-relative"> <!-- btn was class="btn btn-sm btn-info" -->
                    <div class="header text-center">
                        <h5><strong>Da ih Pronadjemo</strong></h5>
                    </div>
                    <p>Imamo i Galeriju <strong>'’Učinimo ih Poznatim’'</strong> gde mozete prepoznati osobu i uneti podatke o njoj i dogadjaju. 
                        Ova opcija u vecoj rezoluciji <a href="{{ url('/fame_all2/') }}" class="btn btn-sm btn-outline-danger toolbar-btn">Galerija 'Učinimo ih poznatim'</a>
                    </p> 
                </div>
                <!-- Navigation arrows -->
                <div class="position-absolute start-0 nav-arrow-container ps-3" style="z-index:1050;">
                    <span id="prevBtn" class="nav-arrow">&#8592;</span>
                </div>

                <div class="position-absolute end-0 nav-arrow-container pe-3" style="z-index:1050;">
                    <span id="nextBtn" class="nav-arrow">&#8594;</span>
                </div>

                <!-- Image -->
                <img id="viewerImage" class="viewer-img" src="" alt="Viewer">

                <!-- Toolbar below image -->
                <div class="d-flex justify-content-center align-items-center mt-3">
                    <div id="toolbar">
                        <!--button id="famous_Btn" class="btn btn-sm btn-outline-danger toolbar-btn">Ucinimo Ga Poznatim</button-->
                        <p>Znate osobu sa slike !!! <strong>’Učinimo je poznatom’&nbsp;</strong>
                        <a id="famousBtn" href="{{ url('/make_famous/') }}" class="btn btn-sm btn-outline-primary toolbar-btn">Učinimo Ga Poznatim</a>
                        <!--span id="filenameLabel" class="ms-2 text-muted small"></span-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ************************************************************* Info Modal ****** -->
<!--@-if($showModal)    if(session('showInfo'))-->
<!--@ if(session('showMainInfoModal'))-->
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          var myModal = new bootstrap.Modal(document.getElementById('viewerModal'));
          myModal.show();
      });
    </script>
<!--@-endif-->
<!-- ************************************************************* Info Modal End *** -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Icons for download icon (optional) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<script>
    (function(){
        const photos = Array.from(document.querySelectorAll('.thumb')).map(t => t.getAttribute('data-filename'));
        const photos_ids = Array.from(document.querySelectorAll('.thumb')).map(t => t.getAttribute('data-id'));
        const photos_descs = Array.from(document.querySelectorAll('.thumb')).map(t => t.getAttribute('data-help-text'));
        let currentIndex = 0;
        const modalEl = document.getElementById('viewerModal');
        const bsModal = new bootstrap.Modal(modalEl, { keyboard: true });
        const viewerImage = document.getElementById('viewerImage');
        //const filenameLabel = document.getElementById('filenameLabel');
        //const downloadBtn = document.getElementById('downloadBtn');
        //const deleteBtn = document.getElementById('deleteBtn');
        const famousBtn = document.getElementById('famousBtn');

        const desctiptionModal = document.getElementById('modal-cnt');

        function assetPath(filename) {
            // Keep this consistent with how controller and assets are served
            return `{{ asset('storage/guess_images/') }}`.replace(/\/$/, '') + '/' + encodeURIComponent(filename);
        }

        function openAt(index) {
            if (photos.length === 0) return;
            currentIndex = (index + photos.length) % photos.length;
            const fname = photos[currentIndex];
            viewerImage.src = assetPath(fname);
            //viewerImage.alt = fname;
            //filenameLabel.textContent = fname;
            desctiptionModal.setAttribute('data-help-text', photos_descs[currentIndex]);
            toastr.info('Right click on photo area for the description.', 'Opis');
            bsModal.show();
        }

        if (photos.length != 0) openAt(0);  //add to have open first famous
        // Click thumbs
        document.querySelectorAll('.thumb').forEach((el, idx) => {
            el.addEventListener('click', () => openAt(idx));
        });

        // Prev / Next
        document.getElementById('prevBtn').addEventListener('click', () => openAt(currentIndex - 1));
        document.getElementById('nextBtn').addEventListener('click', () => openAt(currentIndex + 1));

        // Keyboard navigation
        modalEl.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') openAt(currentIndex - 1);
            if (e.key === 'ArrowRight') openAt(currentIndex + 1);
        });

        // Also allow left/right when modal is open by listening on document
        document.addEventListener('keydown', (e) => {
            if (!document.querySelector('.modal.show')) return;
            if (e.key === 'ArrowLeft') openAt(currentIndex - 1);
            if (e.key === 'ArrowRight') openAt(currentIndex + 1);
        });

        // famousBtn (AJAX POST)
        famousBtn.addEventListener('click', () => {
            const fname = photos[currentIndex];
            const SITEURL = "{{ url('/') }}";
            const fid = photos_ids[currentIndex];
            famousBtn.href = SITEURL + '/make_famous/' + fid;
            if (!confirm(`Make them Famous "${fname}" You are going to add data for the following person.`)) return; 
        });

    })();
</script>

@endsection
