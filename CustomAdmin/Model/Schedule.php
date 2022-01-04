<?php

namespace Magenest\CustomAdmin\Model;

use Magento\Framework\Model\AbstractModel;

class Schedule extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceModel\Schedule::class);
    }
}
