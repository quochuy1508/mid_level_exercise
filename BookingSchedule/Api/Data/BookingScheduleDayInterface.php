<?php

namespace Magenest\BookingSchedule\Api\Data;

/**
 * Interface BookingScheduleDayInterface Data
 */
interface BookingScheduleDayInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID = 'entity_id';
    const DAY = 'day';
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
     * @return BookingScheduleDayInterface
     */
    public function setEntityId($entityId);

    /**
     * Get day
     *
     * @return string
     */
    public function getDay();

    /**
     * Set day
     *
     * @param string $day
     * @return BookingScheduleDayInterface
     */
    public function setDay($day);
}
