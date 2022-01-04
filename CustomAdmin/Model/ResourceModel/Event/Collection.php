<?php

namespace Magenest\CustomAdmin\Model\ResourceModel\Event;

use Magenest\CustomAdmin\Model\Event;
use Magenest\CustomAdmin\Model\ResourceModel\Event as EventResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'event_id';

    protected function _construct()
    {
        $this->_init(Event::class, EventResourceModel::class);
    }
}
