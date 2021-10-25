<?php

namespace Magenest\BookingSchedule\Model;

use Magenest\BookingSchedule\Api\DuplicateBookingScheduleInterface;
use Magenest\BookingSchedule\Model\BookingScheduleDayFactory;
use Magenest\BookingSchedule\Model\BookingScheduleSlotFactory;
use Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleSlot as SlotResourceModel;
use Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleDay as DayResourceModel;
use Magenest\BookingSchedule\Setup\Patch\Data\DataTimeDefault;
use Magento\Framework\Stdlib\DateTime\DateTime;

class DuplicateBookingSchedule implements DuplicateBookingScheduleInterface
{
    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var DayResourceModel
     */
    private $dayResourceModel;

    /**
     * @var SlotResourceModel
     */
    private $slotResourceModel;

    /**
     * @var BookingScheduleDayFactory
     */
    private $bookingScheduleDayFactory;

    /**
     * @var BookingScheduleSlotFactory
     */
    private $bookingScheduleSlotFactory;

    public function __construct(
        DayResourceModel $dayResourceModel,
        SlotResourceModel $slotResourceModel,
        DateTime                 $dateTime,
        BookingScheduleDayFactory                 $bookingScheduleDayFactory,
        BookingScheduleSlotFactory                 $bookingScheduleSlotFactory
    )
    {
        $this->dayResourceModel = $dayResourceModel;
        $this->slotResourceModel = $slotResourceModel;
        $this->dateTime = $dateTime;
        $this->bookingScheduleDayFactory = $bookingScheduleDayFactory;
        $this->bookingScheduleSlotFactory = $bookingScheduleSlotFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute($number)
    {
        try {
            $currentDate = $this->dateTime->date('Y-m-d h:i:s');
            $sunDayThisWeek = date('Y-m-d h:i:s', strtotime('sunday this week', strtotime($currentDate)));
            $mondayNextWeek = date('Y-m-d h:i:s', strtotime('monday next week', strtotime($currentDate)));

            /** @var BookingScheduleDay $dayModel */
            $sunDayThisWeekModel = $this->bookingScheduleDayFactory->create();
            $this->dayResourceModel->load($sunDayThisWeekModel, $sunDayThisWeek, 'day');

            $connection = $this->slotResourceModel->getConnection();
            $connection->delete(
                $connection->getTableName('booking_schedule_day'),
                ['entity_id > ?' => $sunDayThisWeekModel->getEntityId()]
            );

            $dayIdNews = [];
            foreach (range(0, $number * 7 - 1) as $i) {
                /** @var BookingScheduleDay $dayModel */
                $dayModel = $this->bookingScheduleDayFactory->create();
                $day = $this->dateTime->gmtDate('Y-m-d H:i:s', strtotime('+' . $i . ' day', strtotime($mondayNextWeek)));
                $dayModel->setDay($day);
                $this->dayResourceModel->save($dayModel);
                $dayIdNews[] = $dayModel->getId();
            }

            $connection->delete(
                $connection->getTableName('booking_schedule_slot'),
                ['day_id > ?' => $sunDayThisWeekModel->getEntityId()]
            );

            foreach (DataTimeDefault::DATA_TIMES as $timeKey => $timeValue) {
                foreach ($dayIdNews as $dayId) {
                    /** @var BookingScheduleSlot $slotModel */
                    $slotModel = $this->bookingScheduleSlotFactory->create();
                    $slotModel->setDayId($dayId);
                    $slotModel->setTimeId($timeKey+1);
                    $slotModel->setReservation(0);
                    $slotModel->setStock(10);
                    $slotModel->setUsed(0);
                    $this->slotResourceModel->save($slotModel);
                }
            }
            return true;
        } catch (\Exception $exception) {
            return false;
        }

    }
}
