<?php

namespace Magenest\EavModel\Model;

class Merchant extends \Magento\Framework\Model\AbstractModel
{
    const ENTITY = 'merchant';

    protected function _construct()
    {
        $this->_init(\Magenest\EavModel\Model\ResourceModel\Merchant::class);
    }
}
