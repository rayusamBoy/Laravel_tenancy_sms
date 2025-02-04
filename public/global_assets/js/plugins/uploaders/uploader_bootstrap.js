/* ------------------------------------------------------------------------------
 *
 *  # Bootstrap multiple file uploader
 *
 *  Demo JS code for uploader_bootstrap.html page
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var FileUpload = function () {


    //
    // Setup module components
    //

    // Bootstrap file upload
    var _componentFileUpload = function () {
        if (!$().fileinput) {
            console.warn('Warning - fileinput.min.js is not loaded.');
            return;
        }

        //
        // Define variables
        //

        // Modal template
        var modalTemplate = '<div class="modal-dialog modal-lg" role="document">\n' +
            '  <div class="modal-content">\n' +
            '    <div class="modal-header align-items-center">\n' +
            '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
            '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
            '    </div>\n' +
            '    <div class="modal-body">\n' +
            '      <div class="floating-buttons btn-group"></div>\n' +
            '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
            '    </div>\n' +
            '  </div>\n' +
            '</div>\n';

        // Buttons inside zoom modal
        var previewZoomButtonClasses = {
            toggleheader: 'btn btn-icon btn-header-toggle btn-sm',
            fullscreen: 'btn btn-icon btn-sm',
            borderless: 'btn btn-icon btn-sm',
            close: 'btn btn-icon btn-sm'
        };

        // Icons inside zoom modal classes
        var previewZoomButtonIcons = {
            prev: '<i class="material-symbols-rounded">arrow_back</i>',
            next: '<i class="material-symbols-rounded">arrow_forward</i>',
            toggleheader: '<i class="material-symbols-rounded">menu</i>',
            fullscreen: '<i class="material-symbols-rounded font-size-base">fullscreen</i>',
            borderless: '<i class="material-symbols-rounded">border_clear</i>',
            close: '<i class="material-symbols-rounded">close</i>'
        };

        // File actions
        var fileActionSettings = {
            zoomClass: '',
            zoomIcon: '<i class="material-symbols-rounded">visibility</i>',
            dragClass: 'p-2',
            dragIcon: '<i class="material-symbols-rounded">drag_indicator</i>',
            removeClass: '',
            removeErrorClass: 'text-danger',
            removeIcon: '<i class="material-symbols-rounded">delete</i>',
            indicatorNew: '<i class="material-symbols-rounded text-success">add</i>',
            indicatorSuccess: '<i class="material-symbols-rounded file-icon-large text-success">check</i>',
            indicatorError: '<i class="material-symbols-rounded text-danger">close</i>',
            indicatorLoading: '<i class="material-symbols-rounded spinner text-muted">progress_activity</i>'
        };


        //
        // Basic example
        //

        $('.file-input').fileinput({
            browseLabel: 'Browse',
            browseIcon: '<i class="material-symbols-rounded mr-2">add_to_photos</i>',
            uploadIcon: '<i class="material-symbols-rounded mr-2">upload_file</i>',
            removeIcon: '<i class="material-symbols-rounded font-size-base mr-2">remove</i>',
            layoutTemplates: {
                icon: '<i class="material-symbols-rounded">task</i>',
                modal: modalTemplate
            },
            initialCaption: "No file selected",
            previewZoomButtonClasses: previewZoomButtonClasses,
            previewZoomButtonIcons: previewZoomButtonIcons,
            fileActionSettings: fileActionSettings
        });

        //
        // No preview layout
        //

        $('.file-input-preview-none').fileinput({
            previewFileType: 'any',
            showPreview: false,
            browseLabel: 'Browse',
            browseClass: 'btn',
            removeClass: 'btn',
            uploadClass: 'btn bg-success-400',
            browseIcon: '<i class="material-symbols-rounded mr-2">add</i>',
            uploadIcon: '<i class="material-symbols-rounded mr-2">upload_file</i>',
            removeIcon: '<i class="material-symbols-rounded font-size-base mr-2">close</i>',
            layoutTemplates: {
                icon: '<i class="material-symbols-rounded">task</i>',
                main1: "{preview}\n" +
                    "<div class='input-group {class}'>\n" +
                    "   <div class='input-group-prepend'>\n" +
                    "       {browse}\n" +
                    "   </div>\n" +
                    "   {caption}\n" +
                    "   <div class='input-group-append'>\n" +
                    "       {upload}\n" +
                    "       {remove}\n" +
                    "   </div>\n" +
                    "</div>",
            },
            initialCaption: "No file selected",
            fileActionSettings: fileActionSettings
        });


        //
        // Custom layout
        //

        $('.file-input-custom').fileinput({
            previewFileType: 'image',
            browseLabel: 'Select',
            browseClass: 'btn bg-slate-700',
            browseIcon: '<i class="material-symbols-rounded mr-2">browse</i>',
            removeLabel: 'Remove',
            removeClass: 'btn btn-danger',
            removeIcon: '<i class="material-symbols-rounded mr-2">cancel</i>',
            uploadClass: 'btn bg-teal-400',
            uploadIcon: '<i class="material-symbols-rounded mr-2">upload_file</i>',
            layoutTemplates: {
                icon: '<i class="material-symbols-rounded">task</i>',
                modal: modalTemplate
            },
            initialCaption: "Please select image",
            mainClass: 'input-group',
            previewZoomButtonClasses: previewZoomButtonClasses,
            previewZoomButtonIcons: previewZoomButtonIcons,
            fileActionSettings: fileActionSettings
        });


        //
        // Template modifications
        //

        $('.file-input-advanced').fileinput({
            browseLabel: 'Browse',
            browseClass: 'btn',
            removeClass: 'btn',
            uploadClass: 'btn bg-success-400',
            browseIcon: '<i class="material-symbols-rounded mr-2">add</i>',
            uploadIcon: '<i class="material-symbols-rounded mr-2">upload_file</i>',
            removeIcon: '<i class="material-symbols-rounded font-size-base mr-2">close</i>',
            layoutTemplates: {
                icon: '<i class="material-symbols-rounded">task</i>',
                main1: "{preview}\n" +
                    "<div class='input-group {class}'>\n" +
                    "   <div class='input-group-prepend'>\n" +
                    "       {browse}\n" +
                    "   </div>\n" +
                    "   {caption}\n" +
                    "   <div class='input-group-append'>\n" +
                    "       {upload}\n" +
                    "       {remove}\n" +
                    "   </div>\n" +
                    "</div>",
                modal: modalTemplate
            },
            initialCaption: "No file selected",
            previewZoomButtonClasses: previewZoomButtonClasses,
            previewZoomButtonIcons: previewZoomButtonIcons,
            fileActionSettings: fileActionSettings
        });


        //
        // Custom file extensions
        //

        $('.file-input-extensions').fileinput({
            browseLabel: 'Browse',
            browseIcon: '<i class="material-symbols-rounded mr-2">add</i>',
            uploadIcon: '<i class="material-symbols-rounded mr-2">upload_file</i>',
            removeIcon: '<i class="material-symbols-rounded font-size-base mr-2">close</i>',
            layoutTemplates: {
                icon: '<i class="material-symbols-rounded">task</i>',
                modal: modalTemplate
            },
            maxFilesNum: 10,
            allowedFileExtensions: ["jpg", "gif", "png", "txt"],
            initialCaption: "No file selected",
            previewZoomButtonClasses: previewZoomButtonClasses,
            previewZoomButtonIcons: previewZoomButtonIcons,
            fileActionSettings: fileActionSettings
        });


        //
        // Always display preview
        //

        $('.file-input-preview').fileinput({
            browseLabel: 'Browse',
            browseIcon: '<i class="material-symbols-rounded mr-2">add</i>',
            uploadIcon: '<i class="material-symbols-rounded mr-2">upload_file</i>',
            removeIcon: '<i class="material-symbols-rounded font-size-base mr-2">close</i>',
            layoutTemplates: {
                icon: '<i class="material-symbols-rounded">task</i>',
                modal: modalTemplate
            },
            initialPreview: [
                '../../../../global_assets/images/demo/images/1.png',
                '../../../../global_assets/images/demo/images/2.png',
            ],
            initialPreviewConfig: [
                { caption: 'Jane.jpg', size: 930321, key: 1, url: '{$url}', showDrag: false },
                { caption: 'Anna.jpg', size: 1218822, key: 2, url: '{$url}', showDrag: false }
            ],
            initialPreviewAsData: true,
            overwriteInitial: false,
            maxFileSize: 100,
            previewZoomButtonClasses: previewZoomButtonClasses,
            previewZoomButtonIcons: previewZoomButtonIcons,
            fileActionSettings: fileActionSettings
        });


        //
        // Display preview on load
        //

        $('.file-input-overwrite').fileinput({
            browseLabel: 'Browse',
            browseIcon: '<i class="material-symbols-rounded mr-2">add</i>',
            uploadIcon: '<i class="material-symbols-rounded mr-2">upload_file</i>',
            removeIcon: '<i class="material-symbols-rounded font-size-base mr-2">close</i>',
            layoutTemplates: {
                icon: '<i class="material-symbols-rounded">task</i>',
                modal: modalTemplate
            },
            initialPreview: [
                '../../../../global_assets/images/demo/images/1.png',
                '../../../../global_assets/images/demo/images/2.png'
            ],
            initialPreviewConfig: [
                { caption: 'Jane.jpg', size: 930321, key: 1, url: '{$url}' },
                { caption: 'Anna.jpg', size: 1218822, key: 2, url: '{$url}' }
            ],
            initialPreviewAsData: true,
            overwriteInitial: true,
            previewZoomButtonClasses: previewZoomButtonClasses,
            previewZoomButtonIcons: previewZoomButtonIcons,
            fileActionSettings: fileActionSettings
        });


        //
        // AJAX upload
        //

        $('.file-input-ajax').fileinput({
            browseLabel: 'Browse',
            uploadUrl: "http://localhost", // server upload action
            uploadAsync: true,
            maxFileCount: 5,
            initialPreview: [],
            browseIcon: '<i class="material-symbols-rounded mr-2">add</i>',
            uploadIcon: '<i class="material-symbols-rounded mr-2">upload_file</i>',
            removeIcon: '<i class="material-symbols-rounded font-size-base mr-2">close</i>',
            fileActionSettings: {
                removeIcon: '<i class="material-symbols-rounded">delete</i>',
                uploadIcon: '<i class="material-symbols-rounded">upload_file</i>',
                uploadClass: '',
                zoomIcon: '<i class="material-symbols-rounded">visibility</i>',
                zoomClass: '',
                indicatorNew: '<i class="material-symbols-rounded text-success">add</i>',
                indicatorSuccess: '<i class="material-symbols-rounded file-icon-large text-success">check</i>',
                indicatorError: '<i class="material-symbols-rounded text-danger">close</i>',
                indicatorLoading: '<i class="material-symbols-rounded spinner text-muted">progress_activity</i>',
            },
            layoutTemplates: {
                icon: '<i class="material-symbols-rounded">task</i>',
                modal: modalTemplate
            },
            initialCaption: 'No file selected',
            previewZoomButtonClasses: previewZoomButtonClasses,
            previewZoomButtonIcons: previewZoomButtonIcons
        });


        //
        // Misc
        //

        // Disable/enable button
        $('#btn-modify').on('click', function () {
            $btn = $(this);
            if ($btn.text() == 'Disable file input') {
                $('#file-input-methods').fileinput('disable');
                $btn.html('Enable file input');
                alert('Hurray! I have disabled the input and hidden the upload button.');
            }
            else {
                $('#file-input-methods').fileinput('enable');
                $btn.html('Disable file input');
                alert('Hurray! I have reverted back the input to enabled with the upload button.');
            }
        });
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            _componentFileUpload();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    FileUpload.init();
});
