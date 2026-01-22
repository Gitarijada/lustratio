@extends('layouts.apppage')

@section('content')
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!--link href="{-{ asset('css/bootstrap2.min.css') }}" rel="stylesheet" />  <--asset('css/bootstrap.min2.css') = public + css/bootstrap.min2.css-->
    <!--  {-!! HTML::style('css/bootstrap.min2.css') !!}  //this work as well-->
    <style>

        body { background: #f7f7fb; }
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px;
            padding: 24px;
        }
        .area {
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
        .modal-custom {
            max-width: 900px; /* Example custom width */
        }
        /* Optional: Adjust height */
        .modal-custom .modal-content {
            height: 80vh; /* Example custom height */
        }
        .modal-dialog-centered {
            /*position: fixed; /*position: absolute;*/
            display: flex;
            align-items: center; /* This vertically centers the content */
            min-height: calc(100% - (var(--bs-modal-margin) * 2)); /* Ensures full height for centering */
        }
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
        <div class="header">
            <h2>Ucinimo ih Poznatim Galerija</h2>
        <!-- dd(Auth::user()) }}--> 
    </div>
<!--{-{ dd($photos) }} -->
    <p class="text-center text-muted">No data found in the text.</p>

    <div class="area" data-id=1>
        <div style="margin-bottom: 10px;">This is the first line of text. And more...</div>      
    </div>
    <div class="area" data-id=2>
        <div style="margin-bottom: 10px;">This is the second (2) line of text.</div>      
    </div>
    <div class="area" data-id=3>
        <div style="margin-bottom: 10px;">This is the (3) line of text.</div>      
    </div>
    <div class="area" data-id=4>
        <div style="margin-bottom: 10px;">This is the forth (4) line of text. <span>Hello <b>World</b>!</span></div>      
    </div>
    <div class="area" data-id=5>
        <div style="margin-bottom: 10px;">This is the fifth (5) line of text.</div>             
    </div>
    <div class="area" data-id=7>
        <div>This is the seventh (7) line of text. To see the output...</div>
    </div>
    
</div>

<!-- Viewer Modal (Bootstrap) -->
<!--Small: .modal-sm (max-width: 300px) Default: No specific size class (max-width: 500px) Large: .modal-lg (max-width: 800px) Extra Large: .modal-xl (max-width: 1140px)-->
<div class="modal fade" id="viewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center position-relative">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>

                <!-- Navigation arrows -->
                <div class="position-absolute top-50 start-0 translate-middle-y ps-3" style="z-index:1050;">
                    <span id="prevBtn" class="nav-arrow">&#8592;</span>
                </div>
                <div class="position-absolute top-50 end-0 translate-middle-y pe-3" style="z-index:1050;">
                    <span id="nextBtn" class="nav-arrow">&#8594;</span>
                </div>

                <section class="col-md-4">
                    <div class="row header-container justify-content-center">
                        <div class="header">
                            <h3>What to do</h3>
                        </div>
                    </div>

                    <div id="viewerContent" class="card-body">
                        <p>
                        This is the first line.<br>
                        This is the second line.<br>
                        And this is the third line.
                        </p>
                    </div>
                    <!--@-include("event_input-main")
                        @-include("event_input-rest")-->
                </section>

                <!-- Toolbar below image -->
                <div class="d-flex justify-content-center align-items-center mt-3">
                    <div id="toolbar">
                        @auth
                            @if (auth()->user() && isset(Auth::user()->id) && Auth::user()->id < 10)
                                <button id="deleteBtn" class="btn btn-sm btn-outline-danger toolbar-btn">Delete</button>
                            @endif
                        @endauth
                        <a id="famousBtn" href="{{ url('/make_famous/') }}" class="btn btn-sm btn-outline-danger toolbar-btn">Ucinimo Ga Poznatim</a>
                        <!--span id="filenameLabel" class="ms-2 text-muted small"></span-->
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!--script src="https://code.jquery.com/jquery-3.6.0.min.js"></script-->
<!--script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script-->
<!-- Bootstrap Icons for download icon (optional) -->
<!--link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"-->

<script>
    (function(){
        //const photos = Array.from(document.querySelectorAll('.thumb')).map(t => t.getAttribute('data-filename'));
        //const photos_ids = Array.from(document.querySelectorAll('.thumb')).map(t => t.getAttribute('data-id'));
        const div_ids = Array.from(document.querySelectorAll('.area')).map(t => t.getAttribute('data-id'));
        const div_textContents = Array.from(document.querySelectorAll('.area')).map(element => element.innerHTML);
        let currentIndex = 0;
        const modalEl = document.getElementById('viewerModal');
        const viewerContent = document.getElementById('viewerContent');
        const bsModal = new bootstrap.Modal(modalEl, { keyboard: true });
        const famousBtn = document.getElementById('famousBtn');


        function openAt(index) {

            currentIndex = (index + div_textContents.length) % div_textContents.length;
            //if (!confirm(`Let index = "${index}" And going to see div_textContents.length "${div_textContents.length}" for the following currentIndex "${currentIndex}" .`)) return; 

            const txtDisplay = div_textContents[currentIndex];
            viewerContent.innerHTML = txtDisplay;
            //viewerContent.textContent viewerContent.innerText viewerContent.innerHTML

            bsModal.show();
        }

        bsModal.show(); //show once page is load
        //if (div_textContents.length != 0) openAt(0);  //open when page is load
        // Click thumbs
        document.querySelectorAll('.area').forEach((el, idx) => {
            //el.addEventListener('click', () => openAt(idx));
            el.addEventListener('dblclick', () => openAt(idx));
            el.addEventListener('contextmenu', () => { event.preventDefault(); openAt(idx) });
        });

        /*myElement.addEventListener('contextmenu', (event) => {
        // Prevent the default browser context menu from appearing
        event.preventDefault();
        openAt(idx)
        });*/

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

        // Delete photo (AJAX POST)

    })();
</script>
@endsection
