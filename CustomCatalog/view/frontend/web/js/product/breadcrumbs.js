/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'Magento_Theme/js/model/breadcrumb-list'
], function ($, breadcrumbList) {
    'use strict';

    return function (widget) {

        $.widget('mage.breadcrumbs', widget, {
            options: {
                useAsMainBreadCrumbSelector: 'use_as_main_breadcrumb',
            },

            /**
             * Append category and product crumbs.
             *
             * @private
             */
            _appendCatalogCrumbs: function () {
                var categoryCrumbs = this._resolveCategoryCrumbs();
                var isExistUseAsMain = false;
                for (const categoryCrumbsKey in categoryCrumbs) {
                    if (categoryCrumbs[categoryCrumbsKey].useAsMain === true) {
                        isExistUseAsMain = categoryCrumbsKey;
                        break;
                    }
                }

                if (isExistUseAsMain) {
                    categoryCrumbs.slice(isExistUseAsMain).forEach(function (crumbInfo) {
                        breadcrumbList.push(crumbInfo);
                    });

                    if (this.options.product) {
                        breadcrumbList.push(this._getProductCrumb());
                    }
                }

            },

            /**
             * Returns crumb data.
             *
             * @param {Object} menuItem
             * @return {Object}
             * @private
             */
            _getCategoryCrumb: function (menuItem) {
                return {
                    'name': 'category',
                    'label': menuItem.text(),
                    'link': menuItem.attr('href'),
                    'title': '',
                    'useAsMain': menuItem.parent().hasClass(this.options.useAsMainBreadCrumbSelector)
                };
            },
        })

        return $.mage.breadcrumbs;
    };
});
