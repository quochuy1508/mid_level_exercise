<?php

namespace Magenest\BookingSchedule\Api\Data;

/**
 * Interface BookingScheduleTimeInterface Data
 */
interface BookingScheduleTimeInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID = 'entity_id';
    const TIME = 'time';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getEntityId();

    /**
     * Set ID
     *
     * @param int $entityId
     * @return BookingScheduleTimeInterface
     */
    public function setEntityId($entityId);

    /**
     * Get day
     *
     * @return string
     */
    public function getTime();

    /**
     * Set Time
     *
     * @param string $time
     * @return BookingScheduleTimeInterface
     */
    public function setTime($time);
}
