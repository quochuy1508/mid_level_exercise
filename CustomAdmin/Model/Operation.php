<?php

namespace Magenest\CustomAdmin\Model;

use Magenest\CustomAdmin\Api\Data\OperationInterface;
use Magento\Framework\DataObject;

class Operation extends DataObject implements OperationInterface
{
    /**
     * @inheritDoc
     */
    public function getEventId()
    {
        return $this->getData(self::EVENT_ID);
    }

    /**
     * @inheritDoc
     */
    public function setEventId($eventId)
    {
        return $this->setData(self::EVENT_ID, $eventId);
    }

    /**
     * @inheritDoc
     */
    public function getScheduleId()
    {
        return $this->getData(self::SCHEDULE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setScheduleId($scheduleId)
    {
        return $this->setData(self::SCHEDULE_ID, $scheduleId);
    }

    /**
     * @inheritDoc
     */
    public function getNote()
    {
        return $this->getData(self::NOTE);
    }

    /**
     * @inheritDoc
     */
    public function setNote($note)
    {
        return $this->setData(self::NOTE, $note);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerIds()
    {
        return $this->getData(self::CUSTOMER_IDS);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerIds($customerIds)
    {
        return $this->setData(self::CUSTOMER_IDS, $customerIds);
    }
}
