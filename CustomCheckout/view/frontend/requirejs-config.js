var config = {
    'config': {
        'mixins': {
            'Magento_Checkout/js/action/set-shipping-information': {
                'Magenest_CustomCheckout/js/action/set-shipping-information': true
            },
            'Magento_Checkout/js/view/shipping': {
                'Magenest_CustomCheckout/js/shipping-validation-mixin': true
            },
        }
    }
}
