<?php

namespace Magenest\CustomerManagement\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * BookingScheduleDay model
 */
class SavedCart extends AbstractDb
{
    const TABLE_NAME = 'customer_saved_cart';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'entity_id');
    }
}
