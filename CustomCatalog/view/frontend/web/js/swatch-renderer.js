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
        $.widget('mage.SwatchRenderer', SwatchRenderer, {

            /** @inheritdoc */
            _OnClick: function ($this, widget) {
                this._super($this, widget);

                const attributeList = this.options.jsonConfig.attributes;
                var selectedOptions = '.' + widget.options.classes.attributeClass + '[data-option-selected]';
                if (widget.element.find(selectedOptions).length === (attributeList.length - 1)) {
                    let idOptionsSelected = [];
                    widget.element.find(selectedOptions).each(function () {
                        var id = $(this).data('attribute-id');
                        idOptionsSelected.push(parseInt(id));
                    });

                    let difference = attributeList.filter(x => !idOptionsSelected.includes(parseInt(x.id)));
                    if (difference.length === 1) {
                        const remained = difference[0];
                        $(`div[data-attribute-id="${remained.id}"]`).remove();
                        let swatchSelectedValue = [];
                        widget.element.find('.swatch-attribute-selected-option').each(function () {
                            swatchSelectedValue.push($(this).text());
                        });
                        $('.swatch-opt').append(
                        `<div style="margin-top: 50px" class="swatch-attribute ${remained.code}" data-attribute-code="${remained.code}" data-attribute-id="${remained.id}">
                            <div aria-activedescendant="option-label-${remained.code}-${remained.id}" tabIndex="0" aria-invalid="false" aria-required="true"
                                 role="listbox" aria-labelledby="option-label-${remained.code}-${remained.id}"
                                 class="swatch-attribute-options clearfix">
                                 ${remained.options.map((option, index) => `<div style="width: 500px; height: 30px" class="swatch-option text" id="option-label-${remained.code}-${remained.id}-item-${option.id}" index="${index}"
                                     aria-checked="false" aria-describedby="option-label-${remained.code}-${remained.id}" tabIndex="0"
                                     data-option-type="0" data-option-id="${option.id}" data-option-label="${option.label}"
                                     aria-label="${option.label}" role="option" data-thumb-width="250" data-thumb-height="100"
                                     ><div>${remained.label + ':' + option.label} ----------- ${swatchSelectedValue.join(" - ")}</div>
                                </div>`).join("")}
                            </div>
                            <input class="swatch-input super-attribute-select" name="super_attribute[${remained.id}]"
                                   type="text" value="" data-selector="super_attribute[${remained.id}]"
                                   data-validate="{required: true}" aria-required="true" aria-invalid="false"></div>`);
                    }
                }
            }
        });

        return $.mage.SwatchRenderer;
    };
});
