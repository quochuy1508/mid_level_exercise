/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'underscore',
    'productSalableQty',
    'jquery-ui-modules/widget'
], function ($, _, productSalableQty) {
    'use strict';

    return function (SwatchRenderer) {
        $.widget('mage.SwatchRenderer', SwatchRenderer, {

            /** @inheritdoc */
            _OnClick: function ($this, widget) {
                var productVariationsSku = this.options.jsonConfig.qty;

                this._super($this, widget);
                productSalableQty(productVariationsSku[this.getProductId()]);
            },

            /**
             * Get chosen product id
             *
             * @returns int|null
             */
            getProductId: function () {
                var products = this._CalcProducts();

                return _.isArray(products) && products.length === 1 ? products[0] : null;
            },
        });

        return $.mage.SwatchRenderer;
    };
});
