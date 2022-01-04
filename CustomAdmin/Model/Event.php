<?php

namespace Magenest\CustomAdmin\Model;

use Magento\Framework\Model\AbstractModel;

class Event extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceModel\Event::class);
    }
}
