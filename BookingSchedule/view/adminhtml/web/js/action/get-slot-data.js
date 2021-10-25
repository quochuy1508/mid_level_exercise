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
        return function (weekNumberFromNow) {
            'use strict';
            var url = urlBuilder.build("/admin/booking_schedule/slot/ajaxdata?isAjax=true&id=" + weekNumberFromNow);
            console.log(url);
            return $.ajax({
                url: url,
                method: 'POST',
                data: {form_key: window.FORM_KEY},
                showLoader: true,
                beforeSend: function () {
                    $('#loader').show();
                },
                dataType: 'json',
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
