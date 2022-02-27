var config = {
    "map": {
        "*": {
            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': 'Magenest_DeliveryDate/js/model/shipping-save-processor/payload-extender'
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/view/shipping': {
                'Magenest_DeliveryDate/js/mixin/shipping-mixin': true
            },
            'Amazon_Payment/js/view/shipping': {
                'Magenest_DeliveryDate/js/mixin/shipping-mixin': true
            }
        }
    }
};

