<?php

namespace Magenest\BookingSchedule\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
* BookingScheduleDay model
*/
class BookingScheduleDay extends AbstractDb
{
    const TABLE_NAME = 'booking_schedule_day';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'entity_id');
    }
}
