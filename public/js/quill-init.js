/**
 * Quill WYSIWYG Editor Initialization
 * Reusable script for initializing Quill editors across admin forms
 */

function initQuillEditor(selector, options = {}) {
    const defaultOptions = {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [{ 'font': [] }],
                [{ 'size': ['small', false, 'large', 'huge'] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['blockquote', 'code-block'],
                ['link', 'image', 'video'],
                ['clean']
            ]
        },
        placeholder: options.placeholder || 'Write your content here...',
    };

    const mergedOptions = { ...defaultOptions, ...options };
    const quill = new Quill(selector, mergedOptions);

    // Sync Quill content with hidden input on form submit
    const form = document.querySelector(selector).closest('form');
    const hiddenInput = form.querySelector('input[name="' + selector.replace('#', '') + '_content"]');
    
    if (form && hiddenInput) {
        form.addEventListener('submit', function() {
            hiddenInput.value = quill.root.innerHTML;
        });
    }

    return quill;
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { initQuillEditor };
}
