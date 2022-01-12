<?php

namespace Magenest\EavModel\Model\ResourceModel\Merchant;

class Collection extends \Magento\Eav\Model\Entity\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Magenest\EavModel\Model\Merchant::class,
            \Magenest\EavModel\Model\ResourceModel\Merchant::class
        );
    }
}
