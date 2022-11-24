(function ($) {
    'use strict';

    // upload purchase order
    $(document).on('change', '#wcpo-document-file', function (e) {
        const previewArea = $('.wcpo-document-preview');
        if (!this.files.length) {
            previewArea.empty().hide();
        } else if (this.files[0].size > 2097152) {
            console.log('file size')
            alert(wcpo_object.max_file_size)
        } else {
            const file = this.files[0];
            const formData = new FormData();
            formData.append('wcpo-document-file', file);
            formData.append('nonce', wcpo_object.nonce);
            $.ajax({
                url: wcpo_object.ajax_url + '?action=wcpo_upload_purchase_order',
                type: 'POST',
                data: formData,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false,
                success: function (response) {
                    previewArea.empty().show();
                    $('input[name="wcpo-document-file-path"]').val(response.data.file_path);
                    previewArea.append('<span class="wcpo-remove">x</span><span>' + file.name + '</span><img src="' + wcpo_object.icons_url + response.data.file_type + '.png">')
                }
            });
        }
    });

    // delete the uploaded purchase order
    $(document).on('click', '.wcpo-remove', function (e) {
        const previewArea = $('.wcpo-document-preview');
        const file = $('input[name="wcpo-document-file-path"]');
        previewArea.empty().hide();
        if (file.val()) {
            $.ajax({
                type: "post", dataType: "json", url: wcpo_object.ajax_url, data: {
                    action: "wcpo_delete_purchase_order_file", file_path: file.val(), nonce: wcpo_object.nonce
                }, success: function (response) {
                    console.log(response)
                    file.val('')
                    $('#wcpo-document-file').val('')
                }
            })
        }
    });

})(jQuery);
