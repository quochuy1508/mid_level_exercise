<?php

namespace Magenest\BookingSchedule\Model;

use Magenest\BookingSchedule\Api\GetBookingScheduleTimeInterface;
use Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleTime\Collection as TimeCollection;
use Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleTime\CollectionFactory as BookingScheduleTimeCollectionFactory;

class GetBookingScheduleTime implements GetBookingScheduleTimeInterface
{
    /**
     * @var BookingScheduleTimeCollectionFactory
     */
    private $bookingScheduleTimeCollection;

    public function __construct(
        BookingScheduleTimeCollectionFactory $bookingScheduleTimeCollection
    ) {
        $this->bookingScheduleTimeCollection = $bookingScheduleTimeCollection;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var TimeCollection $collection */
        $collection = $this->bookingScheduleTimeCollection->create();

        return $collection->getItems();
    }
}
