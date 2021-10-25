<?php

namespace Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleTime;

use Magenest\BookingSchedule\Model\BookingScheduleTime as BookingScheduleTimeModel;
use Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleTime as BookingScheduleTimeResourceModel;

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
    protected $_eventPrefix = 'booking_schedule_time_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'booking_schedule_time_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(BookingScheduleTimeModel::class, BookingScheduleTimeResourceModel::class);
    }
}
