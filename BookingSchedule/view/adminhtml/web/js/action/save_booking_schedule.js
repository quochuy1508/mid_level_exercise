/*global define,alert*/
define(
    [
        'ko',
        'jquery',
        'mage/storage',
        'mage/translate',
        'mage/url'
    ],
    function (
        ko,
        $,
        storage,
        $t,
        urlBuilder
    ) {
        'use strict';
        return function (arrayChange) {
            'use strict';
            var url = urlBuilder.build("/admin/booking_schedule/slot/save?isAjax=true");
            console.log(url);
            return $.ajax({
                url: url,
                method: 'POST',
                data: {form_key: window.FORM_KEY, data: arrayChange},
                showLoader: true,
                beforeSend: function () {
                    $('#loader').show();
                },
                success: function (res) {
                    return res;
                },
                error: function (xhr, status, error) {
                    return false;
                }
            });
        };
    }
);
