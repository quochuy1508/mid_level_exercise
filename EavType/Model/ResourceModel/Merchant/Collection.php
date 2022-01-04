<?php

namespace Magenest\EavType\Model\ResourceModel\Merchant;

use Magenest\EavType\Model\Merchant;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(Merchant::class, \Magenest\EavType\Model\ResourceModel\Merchant::class);
    }
}
