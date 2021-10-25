<?php

namespace Magenest\Cache\Model\ResourceModel\CustomerDirectory;

use Magenest\Cache\Model\ResourceModel\CustomerDirectory;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'cache_customer_directory_collection';
    protected $_eventObject = 'customer_directory_collection';

    /**
     * @inheirtDoc
     */
    protected function _construct()
    {
        $this->_init(\Magenest\Cache\Model\CustomerDirectory::class, CustomerDirectory::class);
    }
}
