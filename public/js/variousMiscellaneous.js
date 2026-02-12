/**
 * Initializes character counting for a specific textarea
 * @param {string} textareaId - The ID of the textarea element
 * @param {string} countDisplayId - The ID of the element displaying the count
 */
//function initCharCounter(textareaId, countDisplayId) {
window.initCharCounter = function(textareaId, countDisplayId) {
    const textarea = document.getElementById(textareaId);
    const countDisplay = document.getElementById(countDisplayId);

    // Only proceed if BOTH elements exist on the current page
    if (!textarea || !countDisplay) return;

    const maxLength = textarea.getAttribute('maxlength') || 700;

    const updateCount = () => {
        const remaining = maxLength - textarea.value.length;
        countDisplay.textContent = remaining;
        
        // Color feedback
        countDisplay.style.color = (remaining <= 50) ? 'red' : 'inherit';
    };

    textarea.addEventListener('input', updateCount);
    
    // Initial call to set correct count if page is reloaded with old input
    updateCount();
}

/**
 * Initializes character counting with a visual progress bar
 */
//function initProgressBarCounter(textareaId, countDisplayId, progressBarId) {
window.initProgressBarCounter = function(textareaId, countDisplayId, progressBarId) {
    const elementarea = document.getElementById(textareaId);
    const countDisplay = document.getElementById(countDisplayId);
    const progressBar = document.getElementById(progressBarId);

    if (!elementarea || !countDisplay || !progressBar) return;

    //if it's NOT a textarea (because textareas use the native maxlength attribute)
    if (elementarea.tagName !== 'TEXTAREA' && elementarea.tagName !== 'INPUT') {
        // 1. HARD STOP: Prevent typing beyond limit
        elementarea.addEventListener('keydown', (e) => {
            const currentLength = elementarea.innerText.length;
            
            // Allow: Backspace, Delete, Arrow keys, and shortcuts (Ctrl+A, etc.)
            const allowedKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Tab'];
            if (allowedKeys.includes(e.key) || e.ctrlKey || e.metaKey) return;

            // If limit reached and text isn't selected, block input
            if (currentLength >= maxLength && window.getSelection().toString().length === 0) {
                e.preventDefault();
            }
        });
        // 2. PASTE CONTROL: Truncate pasted text
        elementarea.addEventListener('paste', (e) => {
            e.preventDefault();
            const text = (e.originalEvent || e).clipboardData.getData('text/plain');
            const currentLength = elementarea.innerText.length;
            const selectionLength = window.getSelection().toString().length;
            const remainingSpace = maxLength - (currentLength - selectionLength);
            
            if (remainingSpace > 0) {
                const truncatedText = text.substring(0, remainingSpace);
                document.execCommand('insertText', false, truncatedText);
            }
        });
    }

    const getTextLength = () => {
        // If it's a standard form element, use .value
        if (elementarea.tagName === 'TEXTAREA' || elementarea.tagName === 'INPUT') {
            return elementarea.value.length;
        }
        // For DIVs (contentEditable) or any other element, use .innerText
        return elementarea.innerText.length;
    };

    const maxLength = parseInt(elementarea.getAttribute('maxlength')) || 800;

    const updateUI = () => {
        // USE innerText for DIVs instead of .value
        //const currentLength = elementarea.innerText.length; 
        //const currentLength = elementarea.value.length;
        const currentLength = getTextLength();
        const percentage = Math.min((currentLength / maxLength) * 100, 100);

        // Update Text Counter
        countDisplay.textContent = currentLength;
        // Color feedback
        countDisplay.style.color = (currentLength >= (maxLength-50)) ? 'red' : 'inherit';

        // Update Progress Bar
        progressBar.style.width = percentage + '%';
        progressBar.setAttribute('aria-valuenow', currentLength);

        // Dynamic Color Changes via Bootstrap classes
        if (percentage > 90) {
            progressBar.className = 'progress-bar bg-danger';
        } else if (percentage > 70) {
            progressBar.className = 'progress-bar bg-warning';
        } else {
            progressBar.className = 'progress-bar bg-success';
        }
    };

    elementarea.addEventListener('input', updateUI);
    
    // Run once on load to catch existing data (like old input)
    updateUI();
}

/*window.initProgressBarCounterDebug = function(textareaId, countDisplayId, progressBarId) {
    const textarea = document.getElementById(textareaId);
    const countDisplay = document.getElementById(countDisplayId);
    const progressBar = document.getElementById(progressBarId);

    // DEBUG: See what's missing
    if (!textarea) console.warn(`Missing Textarea: ${textareaId}`);
    if (!countDisplay) console.warn(`Missing Count Display: ${countDisplayId}`);
    if (!progressBar) console.warn(`Missing Progress Bar: ${progressBarId}`);

    if (!textarea || !countDisplay || !progressBar) return;

    const maxLength = parseInt(textarea.getAttribute('maxlength')) || 800;

    const updateUI = () => {
        const currentLength = textarea.value.length;
        const percentage = (currentLength / maxLength) * 100;
        countDisplay.textContent = currentLength;
        progressBar.style.width = percentage + '%';
        
        // Color logic...
        if (percentage > 90) progressBar.className = 'progress-bar bg-danger';
        else if (percentage > 70) progressBar.className = 'progress-bar bg-warning';
        else progressBar.className = 'progress-bar bg-success';
    };

    // Remove old listeners to prevent "doubling" if AJAX runs twice
    textarea.removeEventListener('input', updateUI); 
    textarea.addEventListener('input', updateUI);
    
    updateUI();
};*/
