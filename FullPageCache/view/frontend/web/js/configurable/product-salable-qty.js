/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Configurable variation left qty.
 */
define([
    'jquery',
    'underscore',
    'mage/url'
], function ($, _, urlBuilder) {
    'use strict';

    return function (productQty) {
        var selectorInfoStockSkuQty = '.availability.configurable-product-salable',
            selectorInfoStockSkuQtyValue = '.availability.configurable-product-salable > strong',
            productQtyInfoBlock = $(selectorInfoStockSkuQty),
            productQtyInfo = $(selectorInfoStockSkuQtyValue);
            if (productQty) {
                productQtyInfo.text(productQty);
                productQtyInfoBlock.show();
            } else {
                productQtyInfoBlock.hide();
            }

    };
});
