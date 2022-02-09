define([
    'jquery',
    'uiRegistry',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/checkout-data'
], function ($, registry, quote, checkoutData) {
    'use strict';

    return function (Component) {
        return Component.extend({
            validationConfigDeliveryTime: window.checkoutConfig.deliveryTimeAddressValidationConfig,

            /**
             * @return {Boolean}
             */
            validateShippingInformation: function () {
                var self = this;
                let result = false;
                var shippingAddressData = checkoutData.getShippingAddressFromData();

                if (shippingAddressData.custom_attributes && shippingAddressData.custom_attributes.delivery_time) {
                    let deliveryTime = shippingAddressData.custom_attributes.delivery_time;
                     $.ajax({
                        url: this.validationConfigDeliveryTime.deliveryTimeAddressValidationConfigUrl,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            delivery_time: deliveryTime,
                        },
                        success: function(response) {
                            if (response.success !== true) {
                                self.showFormPopUp();
                                $( ".delivery_time_field .control._with-tooltip" ).append(
                                '<div class="field-error" data-bind="attr: { id: element.errorId }"\n' +
                                '     id="error-delivery_time_field">\n' +
                                '     <span data-bind="text: element.error">' + response.message +'</span>\n' +
                                '</div>');
                                result = false;
                            } else {
                                return true;
                            }
                        },
                        error: function (xhr, status, errorThrown) {
                            self.showFormPopUp();
                            result = false;
                        }
                    });
                }
            }
        });
    };
});
