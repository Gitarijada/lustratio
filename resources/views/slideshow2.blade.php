@extends('layouts.apppage')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--link href="{-{ asset('css/bootstrap2.min.css') }}" rel="stylesheet" />  <--asset('css/bootstrap.min2.css') = public + css/bootstrap.min2.css-->
    <!--  {-!! HTML::style('css/bootstrap.min2.css') !!}  //this work as well-->
    <style> 

        .toast-info {
            background-color:#2F96B4;   /*overwrite toastr.info color*/
        }

        body { background: #f7f7fb; }
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
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
            height: 140px;
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
        .viewer-img { max-height: 70vh; max-width: 100%; display:block; margin: 0 auto; }
        .toolbar-btn { margin-right: 8px; }
        .nav-arrow { font-size: 2rem; cursor: pointer; user-select: none; }
    </style>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="container py-3">
    <div class="text-center position-relative">
        <a href="{{ url('/upload_guess_img/') }}" class="btn btn-sm btn-outline-primary toolbar-btn">Dodaj novog 'Učinimo ih poznatim'</a>
    </div>
    <div class="row justify-content-center">
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
        <div class="modal-content item" id="modal-cnt" data-help-title="Dogadjaj u vezi osobe na fotografiji" data-help-text="">
            <div class="modal-body text-center position-relative">

                <!-- Navigation arrows -->
                <div class="position-absolute top-50 start-0 translate-middle-y ps-3" style="z-index:1050;">
                    <span id="prevBtn" class="nav-arrow">&#8592;</span>
                </div>
                <div class="position-absolute top-50 end-0 translate-middle-y pe-3" style="z-index:1050;">
                    <span id="nextBtn" class="nav-arrow">&#8594;</span>
                </div>

                <!-- Image -->
                <img id="viewerImage" class="viewer-img" src="" alt="Viewer">

                <!-- Toolbar below image -->
                <div class="d-flex justify-content-center align-items-center mt-3">
                    <div id="toolbar">
                        <a id="downloadBtn" class="btn btn-sm btn-outline-primary toolbar-btn" href="#" download>
                            <i class="bi bi-download"></i> Download
                        </a>
                        @auth
                            @if (auth()->user() && isset(Auth::user()->id) && Auth::user()->id < 10)
                                <button id="deleteBtn" class="btn btn-sm btn-outline-danger toolbar-btn">Delete</button>
                            @endif
                        @endauth
                        <!--button id="famous_Btn" class="btn btn-sm btn-outline-danger toolbar-btn">Ucinimo Ga Poznatim</button-->
                        <a id="famousBtn" class="btn btn-sm btn-outline-primary toolbar-btn">Učinimo Ga Poznatim</a>
                        <!--span id="filenameLabel" class="ms-2 text-muted small"></span-->
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

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
        const downloadBtn = document.getElementById('downloadBtn');
        const deleteBtn = document.getElementById('deleteBtn');
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
            downloadBtn.href = assetPath(fname);
            downloadBtn.setAttribute('download', fname);
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
            //const fname = photos[currentIndex];   if (!confirm(`Make "${fname}" file.`)) return;
            if (!confirm(`Make them Famous. You are going to add data for the following person.`)) return;
            const SITEURL = "{{ url('/') }}";
            const fid = photos_ids[currentIndex];
            famousBtn.href = SITEURL + '/make_famous/' + fid;
        });

        // Delete photo (AJAX POST)
        deleteBtn.addEventListener('click', () => {
            const fname = photos[currentIndex];
            if (!confirm(`Delete "${fname}"? This cannot be undone.`)) return;

            fetch("{{ route('photos.delete') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ filename: fname })
            })
            .then(r => r.json().then(j => ({ ok: r.ok, status: r.status, json: j })))
            .then(({ ok, status, json }) => {
                if (ok && json.success) {
                    // remove from photos array and from DOM
                    photos.splice(currentIndex, 1);
                    const thumb = document.querySelector(`.thumb[data-filename="${CSS.escape(fname)}"]`);
                    if (thumb) thumb.remove();

                    if (photos.length === 0) {
                        bsModal.hide();
                        document.getElementById('gallery').innerHTML = '<p class="text-center text-muted">No photos left.</p>';
                        return;
                    }

                    // show next (currentIndex stays same because array shrank), but clamp
                    if (currentIndex >= photos.length) currentIndex = photos.length - 1;
                    openAt(currentIndex);
                } else {
                    alert(json.message || 'Delete failed');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error deleting file');
            });

        });

    })();
</script>

@endsection
