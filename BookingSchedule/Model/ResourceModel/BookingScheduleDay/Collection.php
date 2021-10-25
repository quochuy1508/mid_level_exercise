<?php

namespace Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleDay;

use Magenest\BookingSchedule\Model\BookingScheduleDay as BookingScheduleDayModel;
use Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleDay as BookingScheduleDayResourceModel;

/**
 * CMS page collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'booking_schedule_day_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'booking_schedule_day_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(BookingScheduleDayModel::class, BookingScheduleDayResourceModel::class);
    }
}
