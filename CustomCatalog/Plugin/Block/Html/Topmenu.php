<?php

namespace Magenest\CustomCatalog\Plugin\Block\Html;

use Magento\Framework\Data\Tree\Node;

class Topmenu extends \Magento\Theme\Block\Html\Topmenu
{
    /**
     * @inheritDoc
     */
    protected function _getMenuItemClasses(Node $item)
    {
        $result = parent::_getMenuItemClasses($item);

        if ($item->getUseAsMainBreadcrumb()) {
            $result[] = 'use_as_main_breadcrumb';
        }

        return $result;
    }
}
