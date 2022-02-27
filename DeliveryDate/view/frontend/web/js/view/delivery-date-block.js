define([
    'jquery',
    'ko',
    'Magento_Ui/js/form/form'
], function ($, ko, Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Magenest_DeliveryDate/delivery-date-block'
        }
    });
});
