define(
    [
        'jquery',
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/totals',
        'Magento_Catalog/js/price-utils'
    ],
    function ($, Component, quote, totals, priceUtils) {
        "use strict";
        return Component.extend({
            defaults: {
                template: 'Magenest_SalesOperations/checkout/summary/customer-rank-discount'
            },

            totals: quote.getTotals(),

            isDisplayedCustomerRankDiscountTotal: function () {
                return this.getCustomerRankDiscountTotal();
            },

            getCustomerRankDiscountTotal: function () {
                var discountSegments;

                if (!this.totals()) {
                    return null;
                }

                if (this.totals()) {
                    discountSegments = totals.getSegment('customer_rank_discount') ? totals.getSegment('customer_rank_discount').value : null;
                }

                return discountSegments ? this.getFormattedPrice(discountSegments) : null;
            },

            getBaseValue: function() {
                var price = 0;
                if (this.totals()) {
                    price = this.totals().base_customer_rank_discount;
                }
                return priceUtils.formatPrice(price, quote.getBasePriceFormat());
            }
        });
    }
);
