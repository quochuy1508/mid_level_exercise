<?php

namespace Magenest\EavType\Model;

use Magento\Framework\Model\AbstractModel;

class Merchant extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceModel\Merchant::class);
    }
}
