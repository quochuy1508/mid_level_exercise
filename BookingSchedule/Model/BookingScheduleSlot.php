<?php

namespace Magenest\BookingSchedule\Model;

use Magenest\BookingSchedule\Api\Data\BookingScheduleSlotInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class BookingScheduleSlot extends AbstractModel implements BookingScheduleSlotInterface, IdentityInterface
{
    /**
     * CMS block cache tag
     */
    const CACHE_TAG = 'booking_schedule_slot';

    /**#@-*/

    /**#@-*/
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'booking_schedule_slot';

    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleSlot::class);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getEntityId()];
    }

    /**
     * @inheritDoc
     */
    public function getEntityId()
    {
        return (int)$this->getData(self::ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @inheritDoc
     */
    public function getDayId()
    {
        return (int)$this->getData(self::DAY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setDayId($dayId)
    {
        return $this->setData(self::DAY_ID, $dayId);
    }

    /**
     * @inheritDoc
     */
    public function getTimeId()
    {
        return (int)$this->getData(self::TIME_ID);
    }

    /**
     * @inheritDoc
     */
    public function setTimeId($timeId)
    {
        return $this->setData(self::TIME_ID, $timeId);
    }

    /**
     * @inheritDoc
     */
    public function getStock()
    {
        return (int)$this->getData(self::STOCK);
    }

    /**
     * @inheritDoc
     */
    public function setStock($stock)
    {
        return $this->setData(self::STOCK, $stock);
    }

    /**
     * @inheritDoc
     */
    public function getReservation()
    {
        return (int)$this->getData(self::RESERVATION);
    }

    /**
     * @inheritDoc
     */
    public function setReservation($reservation)
    {
        return $this->setData(self::RESERVATION, $reservation);
    }

    /**
     * @inheritDoc
     */
    public function getUsed()
    {
        return (int)$this->getData(self::USED);
    }

    /**
     * @inheritDoc
     */
    public function setUsed($used)
    {
        return $this->setData(self::USED, $used);
    }
}
