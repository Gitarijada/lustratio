@extends('layouts.apppage')

@section('content')
<style>
    .item {
        width: 150px;
        height: 100px;
        background-color: #f9fafb;
        border: 1px solid #ccc;
        border-radius: 8px;
        display: inline-block;
        margin: 10px;
        text-align: center;
        line-height: 100px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .item:hover {
        background-color: #f1f1f1;
    }

    /* Help modal */
    .help-modal {
        position: absolute;
        opacity: 0;
        transform: translateY(-5px);
        pointer-events: none;
        background: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        border-radius: 10px;
        padding: 15px;
        width: 260px;
        z-index: 9999;
        font-size: 14px;
        line-height: 1.5;
        transition: opacity 0.25s ease, transform 0.25s ease;
    }

    .help-modal.visible {
        opacity: 1;
        transform: translateY(0);
        pointer-events: all;
    }

    /* Default arrow (points left) */
    .help-modal::after {
        content: "";
        position: absolute;
        top: 15px;
        left: -8px;
        border-width: 8px;
        border-style: solid;
        border-color: transparent #fff transparent transparent;
        filter: drop-shadow(-1px 0 1px rgba(0,0,0,0.1));
        transition: left 0.2s, right 0.2s;
    }

    /* Flipped arrow (points right) */
    .help-modal.flip::after {
        left: auto;
        right: -8px;
        border-color: transparent transparent transparent #fff;
        filter: drop-shadow(1px 0 1px rgba(0,0,0,0.1));
    }

    .help-title {
        font-weight: bold;
        margin-bottom: 6px;
        color: #333;
    }

    .help-close {
        position: absolute;
        top: 6px;
        right: 10px;
        border: none;
        background: transparent;
        font-size: 16px;
        cursor: pointer;
        color: #666;
        transition: color 0.2s;
    }

    .help-close:hover {
        color: #000;
    }
</style>

<div class="container mt-4">
    <h3>Right-click any box for help</h3>

    <div class="item"
         data-help-title="Box 1 Help"
         data-help-text="This box represents the first category. It may contain photos, files, or text data associated with section one.">
        Box 1
    </div>

    <div class="item"
         data-help-title="Box 2 Info"
         data-help-text="This section is used for internal reports. Right-clicking opens context details specific to the box.">
        Box 2
    </div>

    <div class="item"
         data-help-title="Box 3 Description"
         data-help-text="This is a placeholder for additional metadata or notes about box three.">
        Box 3
    </div>

    <div class="item"
         data-help-title="Box 4 Description"
         data-help-text="This is a placeholder for additional metadata or notes about box three.">
        Box 4
    </div>

    <div class="item"
         data-help-title="Box 5 Description"
         data-help-text="This is a placeholder for additional metadata or notes about box three.">
        Box 5
    </div>

    <div class="item"
         data-help-title="Box 4 Description"
         data-help-text="This is a placeholder for additional metadata or notes about box three.">
        Box 4
    </div>

    <div class="item"
         data-help-title="Box 3 Description"
         data-help-text="This is a placeholder for additional metadata or notes about box three.">
        Box 3
    </div>

    <div class="item"
         data-help-title="Box 2 Info"
         data-help-text="This section is used for internal reports. Right-clicking opens context details specific to the box.">
        Box 2
    </div>
</div>

<!-- Shared modal -->
<div id="helpModal" class="help-modal">
    <button class="help-close" id="closeHelp">&times;</button>
    <div class="help-title" id="helpTitle"></div>
    <div class="help-body" id="helpText"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('helpModal');
        const title = document.getElementById('helpTitle');
        const body = document.getElementById('helpText');
        const closeBtn = document.getElementById('closeHelp');
        let fadeTimeout;

        // Show modal next to element (smart positioning)
        function showModal(item) {
            const rect = item.getBoundingClientRect();
            const windowWidth = window.innerWidth;
            const modalWidth = modal.offsetWidth || 260;
            const spaceRight = windowWidth - rect.right;
            const spaceLeft = rect.left;

            // Reset classes
            modal.classList.remove('flip');

            // If not enough space on right, flip to left
            if (spaceRight < modalWidth + 30 && spaceLeft > modalWidth + 30) {
                modal.style.left = `${rect.left - modalWidth - 20 + window.scrollX}px`;
                modal.classList.add('flip');
            } else {
                modal.style.left = `${rect.right + 15 + window.scrollX}px`;
            }

            modal.style.top = `${rect.top + window.scrollY}px`;

            title.textContent = item.dataset.helpTitle;
            body.textContent = item.dataset.helpText;

            clearTimeout(fadeTimeout);
            modal.style.display = 'block';
            requestAnimationFrame(() => modal.classList.add('visible'));
        }

        function hideModal() {
            modal.classList.remove('visible');
            clearTimeout(fadeTimeout);
            fadeTimeout = setTimeout(() => {
                modal.style.display = 'none';
            }, 250);
        }

        document.querySelectorAll('.item').forEach(item => {
            item.addEventListener('contextmenu', e => {
                e.preventDefault();
                showModal(item);
            });
        });

        closeBtn.addEventListener('click', hideModal);

        document.addEventListener('click', e => {
            if (!modal.contains(e.target) && !e.target.classList.contains('item')) {
                hideModal();
            }
        });
    });
</script>
@endsection
