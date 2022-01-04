<?php

namespace Magenest\CustomAdmin\Model\ResourceModel\Schedule;

use Magenest\CustomAdmin\Model\Schedule;
use Magenest\CustomAdmin\Model\ResourceModel\Schedule as ScheduleResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'event_id';

    protected function _construct()
    {
        $this->_init(Schedule::class, ScheduleResourceModel::class);
    }
}
