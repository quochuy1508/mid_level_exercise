/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'jquery',
    'configurableVariationQty',
    'jquery-ui-modules/widget'
], function ($, configurableVariationQty) {
    'use strict';

    return function (SwatchRenderer) {
        $.widget('mage.SwatchRendererRemandOption', {
            options: {
                delay: 200,                             //how much ms before tooltip to show
                tooltipClass: 'swatch-option-tooltip'  //configurable, but remember about css
            },

            /**
             * @private
             */
            _init: function () {
                var $widget = this,
                    $this = this.element,
                    container = this.element;
            }
        });


        $.widget('mage.SwatchRenderer', SwatchRenderer, {

            /** @inheritdoc */
            _OnClick: function ($this, widget) {
                this._super($this, widget);

                let that = this;
                const attributeList = this.options.jsonConfig.attributes;
                var selectedOptions = '.' + widget.options.classes.attributeClass + '[data-option-selected]';
                if (widget.element.find(selectedOptions).length === (attributeList.length - 1)) {
                    let idOptions = attributeList.map(function(attribute) {return parseInt(attribute.id);});
                    let idOptionsSelected = [];
                    widget.element.find(selectedOptions).each(function () {
                        var id = $(this).data('attribute-id');
                        idOptionsSelected.push(parseInt(id));
                    });
                    console.log(idOptions)
                    console.log(idOptionsSelected)
                    let difference = idOptions.filter(x => !idOptionsSelected.includes(x));
                    if (difference.length === 1) {
                        $(`div[data-attribute-id="${difference[0]}"]`).remove();
                    }
                }
            }
        });

        return $.mage.SwatchRenderer;
    };
});
