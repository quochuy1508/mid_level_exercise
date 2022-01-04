<?php

namespace Magenest\CustomAdmin\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Event extends AbstractDb
{
    const TABLE_NAME = 'magenest_event';

    protected $_idFieldName = 'event_id';

    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, $this->_idFieldName);
    }
}
