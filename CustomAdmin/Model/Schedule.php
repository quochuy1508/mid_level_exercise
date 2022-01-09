<?php

namespace Magenest\CustomAdmin\Model;

use Magento\Framework\Model\AbstractModel;

class Schedule extends AbstractModel
{
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 0;

    protected function _construct()
    {
        $this->_init(ResourceModel\Schedule::class);
    }

    public function getAvailableScheduleDay()
    {
        return [
            self::MONDAY => __('Monday'),
            self::TUESDAY => __('Tuesday'),
            self::WEDNESDAY => __('Wednesday'),
            self::THURSDAY => __('Thursday'),
            self::FRIDAY => __('Friday'),
            self::SATURDAY => __('Saturday'),
            self::SUNDAY => __('Sunday'),
        ];
    }
}
