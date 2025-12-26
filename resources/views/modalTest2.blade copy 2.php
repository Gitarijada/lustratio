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
        display: none;
        background: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        border-radius: 10px;
        padding: 15px;
        width: 250px;
        z-index: 9999;
        font-size: 14px;
        line-height: 1.5;
    }

    .help-modal::after {
        content: "";
        position: absolute;
        top: 10px;
        left: -8px;
        border-width: 8px;
        border-style: solid;
        border-color: transparent #fff transparent transparent;
        filter: drop-shadow(-1px 0 1px rgba(0,0,0,0.1));
    }

    .help-title {
        font-weight: bold;
        margin-bottom: 5px;
        color: #333;
    }

    .help-close {
        position: absolute;
        top: 5px;
        right: 8px;
        border: none;
        background: transparent;
        font-size: 16px;
        cursor: pointer;
        color: #666;
    }
    .help-close:hover {
        color: #000;
    }
</style>

<div class="container mt-4">
    <h3>Right-click any box for help</h3>

    <div class="item" data-help-title="Box 1 Help" data-help-text="This box represents the first category. It may contain photos, files, or text data associated with section one.">Box 1</div>

    <div class="item" data-help-title="Box 2 Info" data-help-text="This section is used for internal reports. Right-clicking opens context details specific to the box. This is a placeholder for additional metadata or notes about box three. This is a placeholder for additional metadata or notes about box three.">Box 2</div>

    <div class="item" data-help-title="Box 3 Description" data-help-text="This is a placeholder for additional metadata or notes about box three.">Box 3</div>
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

        document.querySelectorAll('.item').forEach(item => {
            item.addEventListener('contextmenu', e => {
                e.preventDefault(); // stop browser menu

                // Get help text and title from data attributes
                title.textContent = item.dataset.helpTitle;
                body.textContent = item.dataset.helpText;

                // Position the modal next to the element
                const rect = item.getBoundingClientRect();
                modal.style.left = `${rect.right + 15 + window.scrollX}px`;
                modal.style.top = `${rect.top + window.scrollY}px`;
                modal.style.display = 'block';
            });
        });

        // Hide when clicking close or outside
        closeBtn.addEventListener('click', () => modal.style.display = 'none');
        document.addEventListener('click', e => {
            if (!modal.contains(e.target) && !e.target.classList.contains('item')) {
                modal.style.display = 'none';
            }
        });
    });
</script>
@endsection
