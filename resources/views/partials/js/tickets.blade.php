<script>
    const element = document.getElementById("markdown-editor");
    var options = {
    element: element,
    autosave: {
        enabled: true,
        uniqueId: element.dataset.autosaveUniqueId,
        delay: 1000
    },
    toolbar: 
        [
            {
                name: "bold",
                action: SimpleMDE.toggleBold,
                className: "fa fa-bold",
                title: "Bold"
            },
            {
                name: "italic",
                action: SimpleMDE.toggleItalic,
                className: "fa fa-italic",
                title: "Italic"
            },
            {
                name: "heading",
                action: SimpleMDE.toggleHeadingSmaller,
                className: "fa fa-header",
                title: "Heading"
            },
            "|",
            {
                name: "quote",
                action: SimpleMDE.toggleBlockquote,
                className: "fa fa-quote-left",
                title: "Quote"
            },
            {
                name: "unordered-list",
                action: SimpleMDE.toggleUnorderedList,
                className: "fa fa-list-ul",
                title: "Generic List"
            },
            {
                name: "ordered-list",
                action: SimpleMDE.toggleOrderedList,
                className: "fa fa-list-ol",
                title: "Numbered List"
            },
            "|",
            {
                name: "preview",
                action: SimpleMDE.togglePreview,
                className: "fa fa-eye no-disable",
                title: "Toggle Preview"
            },
            "|",
            {
                name: "guide",
                action: function customFunction(editor) {
                    $("#markdown-guide-modal").modal("show");
                },
                className: "fa fa-question-circle",
                title: "Markdown Guide"
            }
        ]
    };

    var simplemde = new SimpleMDE(options);

    $('button.cancel-editor').click(function() {
        simplemde.value(''); // Reset the editor's value
        // Kill the current one
        simplemde.toTextArea();
        simplemde = null;
        simplemde = new SimpleMDE(options); // Make new instance
        $('.card-ticket').find('.list-icons-item[data-action=collapse]').click(); // Collapse the card
    });

    // Resets the value of the SimpleMDE editor when the 'flash_success' session variable is set, 
    // effectively clearing the editor's content.
    @if (session('flash_success'))
        simplemde.value("");
    @endif

</script>