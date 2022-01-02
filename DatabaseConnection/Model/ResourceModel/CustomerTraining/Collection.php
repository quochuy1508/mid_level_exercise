<?php

namespace Magenest\DatabaseConnection\Model\ResourceModel\CustomerTraining;

use Magenest\DatabaseConnection\Model\CustomerTraining;
use Magenest\DatabaseConnection\Model\ResourceModel\CustomerTraining as CustomerTrainingResourceModel;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(CustomerTraining::class, CustomerTrainingResourceModel::class);
    }
}
