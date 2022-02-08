var config = {
    'config': {
        'mixins': {
            'Magento_Checkout/js/view/shipping': {
                'Magenest_CustomCheckout/js/view/shipping-payment-mixin': true
            },
            'Magento_Checkout/js/view/payment': {
                'Magenest_CustomCheckout/js/view/shipping-payment-mixin': true
            }
        }
    }
}
