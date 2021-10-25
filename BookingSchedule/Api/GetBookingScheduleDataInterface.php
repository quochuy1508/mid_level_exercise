<?php

namespace Magenest\BookingSchedule\Api;

/**
 * Interface GetBookingScheduleDataInterface
 */
interface GetBookingScheduleDataInterface
{
    /**
     * Get Booking Schedule Data Interface for Form Block
     * @param int $weekNumber
     * @return array
     */
    public function execute($weekNumber = 0);
}
