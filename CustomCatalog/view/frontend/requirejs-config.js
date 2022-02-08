/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

var config = {
    config: {
        mixins: {
            'Magento_Swatches/js/swatch-renderer': {
                'Magenest_CustomCatalog/js/swatch-renderer': true
            },
            'Magento_Theme/js/view/breadcrumbs': {
                'Magenest_CustomCatalog/js/product/breadcrumbs': true
            }
        }
    }
};
