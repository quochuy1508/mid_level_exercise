<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\AjaxCartPage\Block\Cart\Item\Renderer\Actions;

/**
 * @api
 * @since 100.0.2
 */
class Remove extends \Magento\Checkout\Block\Cart\Item\Renderer\Actions\Remove
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'Magenest_AjaxCartPage::cart/item/renderer/actions/remove.phtml';

    public function setTemplate($template)
    {
        return parent::setTemplate($this->_template);
    }
}
