/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'uiComponent',
    'Magento_Customer/js/customer-data',
    "jquery",
    "mage/url",
    'ko',
], function (Component, customerData, $, urlBuilder, ko) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Magenest_CustomerManagement/recently-viewed'
        },

        /** @inheritdoc */
        initialize: function () {
            this._super();
            this.productViewedArray = ko.observableArray([]);

            let self = this;
            let productViewed = JSON.parse(window.localStorage.getItem('recently_viewed_product'));
            var productArray = Object.keys(productViewed)
                // iterate over them and generate the array
                .map(function(k) {
                    // generate the array element
                    return productViewed[k];
                }).slice(-3);

            var dataPost = JSON.stringify(productArray.map(e => e.product_id));
            var url = urlBuilder.build('customermanagement/recently_viewed/info');

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    dataProduct: dataPost,
                },
                success: function(response) {
                    self.productViewedArray.push(...JSON.parse(response));
                },
                error: function (xhr, status, errorThrown) {
                }
            });

        },
    });
});
