var config = {
    map: {
        '*': {
            productSalableQty: 'Magenest_FullPageCache/js/configurable/product-salable-qty'
        }
    },
    config: {
        mixins: {
            'Magento_Swatches/js/swatch-renderer': {
                'Magenest_FullPageCache/js/configurable/productQty': true
            }
        }
    }
};
