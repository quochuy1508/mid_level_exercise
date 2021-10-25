<?php

namespace Magenest\BookingSchedule\Model;

use Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleSlot\Collection as SlotCollection;
use Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleSlot\CollectionFactory as BookingScheduleSlotCollectionFactory;
use Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleDay\Collection as DayCollection;
use Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleDay\CollectionFactory as BookingScheduleDayCollectionFactory;
use Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleDay;
use Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class GetBookingScheduleData implements \Magenest\BookingSchedule\Api\GetBookingScheduleDataInterface
{
    const NAME_DAY_OF_WEEK = ['Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday'];
    /**
     * @var BookingScheduleSlotCollectionFactory
     */
    private $bookingScheduleSlotCollection;


    /**
     * @var BookingScheduleDayCollectionFactory
     */
    private $bookingScheduleDayCollection;

    /**
     * @var TimezoneInterface
     */
    protected $localeDate;

    public function __construct(
        BookingScheduleSlotCollectionFactory $bookingScheduleSlotCollection,
        BookingScheduleDayCollectionFactory $bookingScheduleDayCollection,
        TimezoneInterface $localeDate
    ) {
        $this->bookingScheduleSlotCollection = $bookingScheduleSlotCollection;
        $this->bookingScheduleDayCollection = $bookingScheduleDayCollection;
        $this->localeDate                  = $localeDate;
    }

    /**
     * @inheritDoc
     */
    public function execute($weekNumber = 0)
    {
        /** @var SlotCollection $collection */
        $collection = $this->bookingScheduleSlotCollection->create();

        /** @var DayCollection $dayCollection */
        $dayCollection = $this->bookingScheduleDayCollection->create();

        $currentDate = $this->localeDate->date()->format("Y-m-d h:i:s");
        $dayOfWeekNumber = date('Y-m-d h:i:s', strtotime($weekNumber * 7 . ' days', strtotime($currentDate)));
        $monday = date('Y-m-d', strtotime('monday this week', strtotime($dayOfWeekNumber)));
        $sunday = date('Y-m-d', strtotime('monday next week', strtotime($dayOfWeekNumber)));

        $dayCollection->getSelect()->where(
            'main_table.day >= ?',
            $monday
        )
        ->where(
            'main_table.day < ?',
            $sunday
        )->order(['main_table.day ASC']);


        $collection->getSelect()
            ->join(['day_table' => $collection->getTable(BookingScheduleDay::TABLE_NAME)], 'day_table.entity_id = main_table.day_id', 'day')
            ->join(['time_table' => $collection->getTable(BookingScheduleTime::TABLE_NAME)], 'time_table.entity_id = main_table.time_id', 'time')
            ->where(
                'day_table.day >= ?',
                $monday
            )
            ->where(
                'day_table.day < ?',
                $sunday
            )
            ->order(['day_table.day ASC', 'time_table.time ASC', 'main_table.time_id ASC']);
        $result = [];
        $headerData = [];
        $headerData[] = [
            'name' => "Time Slot"
        ];
        foreach ($dayCollection->getItems() as $item) {
            $dayOfWeek = (int)date('w', strtotime($item['day']));
            $nameDayOfWeek = self::NAME_DAY_OF_WEEK[$dayOfWeek];
            $dayAndMonth = $nameDayOfWeek . ' ' . $this->localeDate->date($item['day'])->format("d-m");
            $headerData[] = [
                'name' => $dayAndMonth
            ];
        }
        foreach ($collection->getItems() as $item) {
            $dayOfWeek = (int)date('w', strtotime($item['day']));
            $nameDayOfWeek = self::NAME_DAY_OF_WEEK[$dayOfWeek];
            $keyExists = $this->checkIsExistTimeInArray($result, 'time', $item['time']);
            if ($keyExists !== -1) {
                $result[$keyExists][$nameDayOfWeek] = [
                    'stock' => $item['stock'],
                    'reservation' => $item['reservation'],
                    'used' => $item['used'],
                    'entity_id' => $item['entity_id']
                ];
            } else {
                $result[] = [
                    'time' => $item['time'],
                    $nameDayOfWeek => [
                        'stock' => $item['stock'],
                        'reservation' => $item['reservation'],
                        'used' => $item['used'],
                        'entity_id' => $item['entity_id']
                    ]
                ];
            }
        }
        return [
            'headerData' => $headerData,
            'data' => $result
        ];
    }

    /**
     * @param array $array
     * @param string $key
     * @param string $val
     * @return int
     */
    private function checkIsExistTimeInArray($array, $key, $val): int
    {
        foreach ($array as $id => $item)
            if (isset($item[$key]) && $item[$key] == $val) {
                return $id;
            }

        return -1;
    }
}
