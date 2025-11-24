/**
 * Auto-fill title field from uploaded PDF file name
 */
+function ($) { "use strict";

    $(document).ready(function() {
        console.log('Monev form auto-fill script loaded');

        // Method 1: Listen for OctoberCMS fileupload events
        $(document).on('upload.oc.fileupload', '[data-control="fileupload"]', function(e, fileInfo) {
            // console.log('File upload detected:', fileInfo);

            if (fileInfo && fileInfo.file && fileInfo.file.name) {
                var fileName = fileInfo.file.name;
                // console.log('Original filename:', fileName);

                // Remove .pdf extension and clean the filename
                var cleanName = fileName.replace(/\.pdf$/i, '');
                cleanName = cleanName.replace(/^[a-f0-9]{20,}\./, '');
                cleanName = cleanName.replace(/^[a-f0-9]{20,}_/, '');

                // console.log('Cleaned filename:', cleanName);

                // Find title input field with multiple selectors
                var titleInput = $('input[name="Monev[title]"]');
                if (titleInput.length === 0) {
                    titleInput = $('#Form-field-Monev-title');
                }
                if (titleInput.length === 0) {
                    titleInput = $('input[data-field-name="title"]');
                }

                // console.log('Title input found:', titleInput.length > 0);

                if (titleInput.length > 0) {
                    titleInput.val(cleanName);
                    titleInput.trigger('change');
                    titleInput.trigger('input');
                    // console.log('Title auto-filled:', cleanName);
                }
            }
        });

        // Method 2: Listen for native file input change
        $(document).on('change', 'input[type="file"]', function(e) {
            var fileInput = e.target;
            // console.log('File input changed');

            if (fileInput.files && fileInput.files.length > 0) {
                var file = fileInput.files[0];
                var fileName = file.name;
                // console.log('File selected:', fileName);

                // Remove .pdf extension and clean the filename
                var cleanName = fileName.replace(/\.pdf$/i, '');
                cleanName = cleanName.replace(/^[a-f0-9]{20,}\./, '');
                cleanName = cleanName.replace(/^[a-f0-9]{20,}_/, '');

                // Find title input field
                var titleInput = $('input[name="Monev[title]"]');
                if (titleInput.length === 0) {
                    titleInput = $('#Form-field-Monev-title');
                }

                if (titleInput.length > 0) {
                    titleInput.val(cleanName);
                    titleInput.trigger('change');
                    titleInput.trigger('input');
                    // console.log('Title auto-filled from file input:', cleanName);
                }
            }
        });

        // Method 3: Watch for file added to the upload list
        $(document).on('DOMNodeInserted', '.upload-object', function() {
            // Get the filename from the upload object
            var $uploadObject = $(this);
            var fileName = $uploadObject.find('.upload-file-name').text();

            if (fileName) {
                // console.log('File added to upload list:', fileName);

                var cleanName = fileName.replace(/\.pdf$/i, '');
                cleanName = cleanName.replace(/^[a-f0-9]{20,}\./, '');
                cleanName = cleanName.replace(/^[a-f0-9]{20,}_/, '');

                var titleInput = $('input[name="Monev[title]"]');
                if (titleInput.length === 0) {
                    titleInput = $('#Form-field-Monev-title');
                }

                if (titleInput.length > 0 && !titleInput.val()) {
                    titleInput.val(cleanName);
                    titleInput.trigger('change');
                    // console.log('Title auto-filled from upload list:', cleanName);
                }
            }
        });
    });

}(window.jQuery);
