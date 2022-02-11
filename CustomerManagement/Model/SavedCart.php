<?php

namespace Magenest\CustomerManagement\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class SavedCart extends AbstractModel implements IdentityInterface
{
    /**
     * CMS block cache tag
     */
    const CACHE_TAG = 'saved_cart';

    /**#@-*/

    /**#@-*/
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'saved_cart';

    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Magenest\CustomerManagement\Model\ResourceModel\SavedCart::class);
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getEntityId()];
    }
}
