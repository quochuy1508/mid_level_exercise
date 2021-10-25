<?php

namespace Magenest\BookingSchedule\Api\Data;

/**
 * Interface BookingScheduleSlotInterface Data
 */
interface BookingScheduleSlotInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID = 'entity_id';
    const DAY_ID = 'day_id';
    const TIME_ID = 'time_id';
    const STOCK = 'stock';
    const RESERVATION = 'reservation';
    const USED = 'used';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getEntityId();

    /**
     * Set ID
     * @param int $entityId
     * @return BookingScheduleSlotInterface
     */
    public function setEntityId($entityId);

    /**
     * Get day id
     *
     * @return int
     */
    public function getDayId();

    /**
     * Set day id
     *
     * @param int $dayId
     * @return BookingScheduleSlotInterface
     */
    public function setDayId($dayId);

    /**
     * Get time id
     *
     * @return int
     */
    public function getTimeId();

    /**
     * Set time id
     *
     * @param int $timeId
     * @return BookingScheduleSlotInterface
     */
    public function setTimeId($timeId);

    /**
     * Get Stock
     *
     * @return int
     */
    public function getStock();

    /**
     * Set Stock
     *
     * @param int $stock
     * @return BookingScheduleSlotInterface
     */
    public function setStock($stock);

    /**
     * Get reservation
     *
     * @return int
     */
    public function getReservation();

    /**
     * Set reservation
     *
     * @param int $reservation
     * @return BookingScheduleSlotInterface
     */
    public function setReservation($reservation);

    /**
     * Get used
     *
     * @return int
     */
    public function getUsed();

    /**
     * Set used
     *
     * @param int $used
     * @return BookingScheduleSlotInterface
     */
    public function setUsed($used);
}
