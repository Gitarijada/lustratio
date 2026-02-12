//With MutationObserver:
//you initialize your modal only once
//afterwards ANY .item element added to the page (via AJAX, Livewire, Vue, etc.)
//→ instantly gets right-click help behavior
//no need for HelpModal.init()... no duplicate event handlers ...no timing issues
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('helpModal'),
          title = document.getElementById('helpTitle'),
          body  = document.getElementById('helpText'),
          close = document.getElementById('closeHelp');
    let fade;

    function show(item){
        const r = item.getBoundingClientRect(),
              w = modal.offsetWidth || 260,
              ww = innerWidth;
        modal.classList.remove('flip');

        if ((ww - r.right) < w+30 && r.left > w+30) {
            modal.style.left = `${r.left - w - 20 + scrollX}px`;
            modal.classList.add('flip');
        } else {
            modal.style.left = `${r.right + 15 + scrollX}px`;
        }
        modal.style.top = `${r.top + scrollY}px`;

        title.textContent = item.dataset.helpTitle;
        body.textContent  = item.dataset.helpText;

        clearTimeout(fade);
        modal.style.display = 'block';
        requestAnimationFrame(() => modal.classList.add('visible'));
    }

    function hide(){
        modal.classList.remove('visible');
        clearTimeout(fade);
        fade = setTimeout(() => modal.style.display='none', 250);
    }

    function bind(root){
        root.querySelectorAll('.item').forEach(i => {
            if (!i.dataset.helpBound){
                i.dataset.helpBound = 1;
                i.addEventListener('contextmenu', e => { e.preventDefault(); show(i); });
            }
        });
    }

    // bind existing items
    bind(document);

    // auto-bind future .item from AJAX / dynamic content
    new MutationObserver(muts => {
        muts.forEach(m => m.addedNodes.forEach(n => {
            if (n.nodeType === 1) bind(n);
        }));
    }).observe(document.body, { childList:true, subtree:true });

    close.addEventListener('click', hide);
    document.addEventListener('click', e => {
        if (!modal.contains(e.target) && !e.target.classList.contains('item')) hide();
    });
});

