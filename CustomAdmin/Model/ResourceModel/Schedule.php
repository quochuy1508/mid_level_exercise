<?php

namespace Magenest\CustomAdmin\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Schedule extends AbstractDb
{
    const TABLE_NAME = 'magenest_schedule';

    protected $_idFieldName = 'schedule_id';

    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, $this->_idFieldName);
    }
}
