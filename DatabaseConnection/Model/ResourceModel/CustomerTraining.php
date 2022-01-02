<?php

namespace Magenest\DatabaseConnection\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class CustomerTraining extends AbstractDb
{
    const TABLE_NAME = 'customer_training';

    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, $this->_idFieldName);
    }
}
