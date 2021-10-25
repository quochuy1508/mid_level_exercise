<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magenest\BookingSchedule\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Data Time Booking Schedule Default
 */
class DataTimeDefault implements DataPatchInterface
{
    const DATA_TIMES = ['06:30', '07:00', '07:30', '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00',
        '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30',
        '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00'];
    /**
     * ModuleDataSetupInterface
     *
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * AddProductAttribute constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        DateTime                 $dateTime
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->dateTime = $dateTime;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $setup = $this->moduleDataSetup;

        $setup->getConnection()->insertArray(
            $setup->getTable('booking_schedule_time'),
            ['time'],
            self::DATA_TIMES
        );

        $dataDays = [];
        $currentDate = $this->dateTime->date('Y-m-d h:i:s');
        $monday = date('Y-m-d h:i:s', strtotime('monday this week', strtotime($currentDate)));

        foreach (range(0, 6) as $number) {
            $dataDays[] = $this->dateTime->gmtDate('Y-m-d H:i:s', strtotime('+' . $number . ' day', strtotime($monday)));
        }

        $setup->getConnection()->insertArray(
            $setup->getTable('booking_schedule_day'),
            ['day'],
            $dataDays
        );

        $dataSlot = [];
        foreach (self::DATA_TIMES as $timeKey => $timeValue) {
            foreach ($dataDays as $dayKey => $dayValue) {
                $dataSlot[] = [
                    'day_id' => $dayKey + 1,
                    'time_id' => $timeKey + 1,
                    'stock' => 10,
                    'reservation' => 0,
                    'used' => 0
                ];
            }
        };

        $setup->getConnection()->insertArray(
            $setup->getTable('booking_schedule_slot'),
            ['day_id', 'time_id', 'stock', 'reservation', 'used'],
            $dataSlot
        );

        $this->moduleDataSetup->endSetup();
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }
}
