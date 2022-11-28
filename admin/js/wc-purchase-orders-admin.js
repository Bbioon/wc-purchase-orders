(function ($) {
    'use strict';

    /**
     * Send request to dismiss the admin notice and save the state to user profile.
     */
    $(document).on('click', '.wc-purchase-orders button.notice-dismiss', function (e) {
        $.ajax({
            type: "post", dataType: "application/json", url: wcpo_object.ajax_url, data: {
                action: "wcpo_dismiss_admin_notice", nonce: wcpo_object.nonce
            }
        })
    });
})(jQuery);
