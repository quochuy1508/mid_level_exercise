<?php

namespace Magenest\Cache\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class CustomerDirectory extends AbstractModel implements IdentityInterface
{
    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'magenest_customer_diretory';
    /**
     * @var string
     */
    protected $_cacheTag = 'magenest_customer_diretory';
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'magenest_customer_diretory';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\CustomerDirectory::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getEntityId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
