<?php

namespace Magenest\BookingSchedule\Model;

use Magenest\BookingSchedule\Api\Data\BookingScheduleTimeInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Model BookingScheduleDay
 */
class BookingScheduleTime extends AbstractModel implements BookingScheduleTimeInterface, IdentityInterface
{
    /**
     * CMS block cache tag
     */
    const CACHE_TAG = 'booking_schedule_time';

    /**#@-*/

    /**#@-*/
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'booking_schedule_time';

    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleTime::class);
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
    public function getTime()
    {
        return $this->getData(self::TIME);
    }

    /**
     * @inheritDoc
     */
    public function setTime($time)
    {
        return $this->setData(self::TIME, $time);
    }
}
