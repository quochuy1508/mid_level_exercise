<?php

namespace Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleSlot;

use Magenest\BookingSchedule\Model\BookingScheduleSlot as BookingScheduleSlotModel;
use Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleSlot as BookingScheduleSlotResourceModel;

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
    protected $_eventPrefix = 'booking_schedule_slot_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'booking_schedule_slot_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(BookingScheduleSlotModel::class, BookingScheduleSlotResourceModel::class);
    }
}
