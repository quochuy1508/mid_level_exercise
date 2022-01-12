<?php

namespace Magenest\CustomAdmin\Api\Data;

interface OperationInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const CUSTOMER_IDS = 'customer_ids';
    const EVENT_ID = 'event_id';
    const SCHEDULE_ID = 'schedule_id';
    const NOTE = 'note';
    /**#@-*/

    /**
     * Customer ids
     *
     * @return string
     * @since 103.0.0
     */
    public function getCustomerIds();

    /**
     * Set Customer ids
     *
     * @param string $customerIds
     * @return $this
     * @since 103.0.0
     */
    public function setCustomerIds($customerIds);

    /**
     * Event id
     *
     * @return int
     * @since 103.0.0
     */
    public function getEventId();

    /**
     * Set Event id
     *
     * @param int $eventId
     * @return $this
     * @since 103.0.0
     */
    public function setEventId($eventId);

    /**
     * Schedule id
     *
     * @return int
     * @since 103.0.0
     */
    public function getScheduleId();

    /**
     * Set Schedule id
     *
     * @param int $scheduleId
     * @return $this
     * @since 103.0.0
     */
    public function setScheduleId($scheduleId);

    /**
     * Note
     *
     * @return string
     * @since 103.0.0
     */
    public function getNote();

    /**
     * Set Note
     *
     * @param string $note
     * @return $this
     * @since 103.0.0
     */
    public function setNote($note);
}
