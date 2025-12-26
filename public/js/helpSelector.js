const HelpModal = {
    modal: null,
    title: null,
    body: null,
    closeBtn: null,
    fadeTimeout: null,

    init() {
        // cache DOM only once
        if (!this.modal) {
            this.modal = document.getElementById('helpModal');
            this.title = document.getElementById('helpTitle');
            this.body  = document.getElementById('helpText');
            this.closeBtn = document.getElementById('closeHelp');

            this.closeBtn.addEventListener('click', () => this.hide());
            document.addEventListener('click', (e) => {
                if (!this.modal.contains(e.target) && !e.target.classList.contains('item')) {
                    this.hide();
                }
            });
        }

        // Attach listeners to ALL current .item elements
        document.querySelectorAll('.item').forEach(item => {
            // prevent duplicate binding
            if (!item.dataset.helpBound) {
                item.addEventListener('contextmenu', (e) => {
                    e.preventDefault();
                    this.show(item);
                });
                item.dataset.helpBound = "1";
            }
        });
    },

    show(item) {
        const rect = item.getBoundingClientRect();
        const modalWidth = this.modal.offsetWidth || 260;
        const windowWidth = window.innerWidth;
        const spaceRight = windowWidth - rect.right;
        const spaceLeft  = rect.left;

        this.modal.classList.remove('flip');

        if (spaceRight < modalWidth + 30 && spaceLeft > modalWidth + 30) {
            this.modal.style.left = `${rect.left - modalWidth - 20 + window.scrollX}px`;
            this.modal.classList.add('flip');
        } else {
            this.modal.style.left = `${rect.right + 15 + window.scrollX}px`;
        }

        this.modal.style.top = `${rect.top + window.scrollY}px`;

        this.title.textContent = item.dataset.helpTitle;
        this.body.textContent  = item.dataset.helpText;

        clearTimeout(this.fadeTimeout);
        this.modal.style.display = "block";
        requestAnimationFrame(() => this.modal.classList.add('visible'));
    },

    hide() {
        this.modal.classList.remove('visible');
        clearTimeout(this.fadeTimeout);
        this.fadeTimeout = setTimeout(() => {
            this.modal.style.display = 'none';
        }, 250);
    }
};

// initialize on first load
document.addEventListener('DOMContentLoaded', () => HelpModal.init());