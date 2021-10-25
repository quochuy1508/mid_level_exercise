<?php

namespace Magenest\BookingSchedule\Block\Adminhtml;

use Magenest\BookingSchedule\Api\GetBookingScheduleDataInterface;
use Magenest\BookingSchedule\Api\GetBookingScheduleTimeInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Serialize\SerializerInterface;

class BookingSchedule extends Template
{
    /**
     * @var GetBookingScheduleDataInterface
     */
    private $getBookingScheduleData;

    /**
     * @var GetBookingScheduleTimeInterface
     */
    private $getBookingScheduleTime;

    /**
     * @var SerializerInterface
     */
    private $serializerInterface;

    public function __construct(
        Template\Context $context,
        GetBookingScheduleDataInterface $getBookingScheduleData,
        GetBookingScheduleTimeInterface $getBookingScheduleTime,
        SerializerInterface $serializerInterface,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->getBookingScheduleData = $getBookingScheduleData;
        $this->getBookingScheduleTime = $getBookingScheduleTime;
        $this->serializerInterface = $serializerInterface;
    }

    public function getBookingScheduleData()
    {
        return $this->getBookingScheduleData->execute();
    }

    public function getBookingScheduleTime()
    {
        return $this->getBookingScheduleTime->execute();
    }

    public function getBookingScheduleSlotString()
    {
        return $this->serializerInterface->serialize($this->getBookingScheduleData->execute()['data']);
    }

    public function getBookingScheduleHeaderString()
    {
        return $this->serializerInterface->serialize($this->getBookingScheduleData->execute()['headerData']);
    }
}
