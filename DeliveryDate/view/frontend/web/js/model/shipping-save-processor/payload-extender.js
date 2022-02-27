define(['jquery',], function ($) {
    'use strict';

    return function (payload) {
        payload.addressInformation['extension_attributes'] = {
            'delivery_date' : $('[name="delivery_date"]').val() ?? null,
            'delivery_comment' : $('[name="delivery_comment"]').val() ?? null
        };

        return payload;
    };
});
