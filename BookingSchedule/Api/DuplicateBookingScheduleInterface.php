<?php

namespace Magenest\BookingSchedule\Api;

/**
 * Interface DuplicateBookingScheduleInterface
 */
interface DuplicateBookingScheduleInterface
{
    /**
     * Get Booking Schedule Time Interface for Form Block
     * @param int $number
     * @return bool
     */
    public function execute($number);
}
