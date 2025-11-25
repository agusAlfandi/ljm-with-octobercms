/**
 * Auto-fill title field from uploaded PDF file name for RTM
 */
+function ($) { "use strict";

    $(document).ready(function() {
        console.log('RTM form auto-fill script loaded');

        // Method 1: Listen for OctoberCMS fileupload events
        $(document).on('upload.oc.fileupload', '[data-control="fileupload"]', function(e, fileInfo) {
            if (fileInfo && fileInfo.file && fileInfo.file.name) {
                var fileName = fileInfo.file.name;

                // Remove .pdf extension and clean the filename
                var cleanName = fileName.replace(/\.pdf$/i, '');
                cleanName = cleanName.replace(/^[a-f0-9]{20,}\./, '');
                cleanName = cleanName.replace(/^[a-f0-9]{20,}_/, '');

                // Find title input field with multiple selectors
                var titleInput = $('input[name="Monev_survey_kepuasan[title]"]');
                if (titleInput.length === 0) {
                    titleInput = $('#Form-field-MonevSurveyKepuasan-title');
                }
                if (titleInput.length === 0) {
                    titleInput = $('input[data-field-name="title"]');
                }

                if (titleInput.length > 0) {
                    titleInput.val(cleanName);
                    titleInput.trigger('change');
                    titleInput.trigger('input');
                }
            }
        });

        // Method 2: Listen for native file input change
        $(document).on('change', 'input[type="file"]', function(e) {
            var fileInput = e.target;

            if (fileInput.files && fileInput.files.length > 0) {
                var file = fileInput.files[0];
                var fileName = file.name;

                // Remove .pdf extension and clean the filename
                var cleanName = fileName.replace(/\.pdf$/i, '');
                cleanName = cleanName.replace(/^[a-f0-9]{20,}\./, '');
                cleanName = cleanName.replace(/^[a-f0-9]{20,}_/, '');

                // Find title input field
                var titleInput = $('input[name="Monev_survey_kepuasan[title]"]');
                if (titleInput.length === 0) {
                    titleInput = $('#Form-field-MonevSurveyKepuasan-title');
                }

                if (titleInput.length > 0) {
                    titleInput.val(cleanName);
                    titleInput.trigger('change');
                    titleInput.trigger('input');
                }
            }
        });

        // Method 3: Watch for file added to the upload list
        $(document).on('DOMNodeInserted', '.upload-object', function() {
            var $uploadObject = $(this);
            var fileName = $uploadObject.find('.upload-file-name').text();

            if (fileName) {
                var cleanName = fileName.replace(/\.pdf$/i, '');
                cleanName = cleanName.replace(/^[a-f0-9]{20,}\./, '');
                cleanName = cleanName.replace(/^[a-f0-9]{20,}_/, '');

                var titleInput = $('input[name="Monev_survey_kepuasan[title]"]');
                if (titleInput.length === 0) {
                    titleInput = $('#Form-field-MonevSurveyKepuasan-title');
                }

                if (titleInput.length > 0 && !titleInput.val()) {
                    titleInput.val(cleanName);
                    titleInput.trigger('change');
                }
            }
        });
    });

}(window.jQuery);
