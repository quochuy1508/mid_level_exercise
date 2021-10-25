<?php

namespace Magenest\BookingSchedule\Model;

use Magenest\BookingSchedule\Api\Data\BookingScheduleDayInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Model BookingScheduleDay
 */
class BookingScheduleDay extends AbstractModel implements BookingScheduleDayInterface, IdentityInterface
{
    /**
     * CMS block cache tag
     */
    const CACHE_TAG = 'booking_schedule_day';

    /**#@-*/

    /**#@-*/
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'booking_schedule_day';

    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\BookingScheduleDay::class);
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
    public function getDay()
    {
        return $this->getData(self::DAY);
    }

    /**
     * @inheritDoc
     */
    public function setDay($day)
    {
        return $this->setData(self::DAY, $day);
    }
}
