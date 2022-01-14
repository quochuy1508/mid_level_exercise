<?php

namespace Magenest\EavModel\Model\ResourceModel\Attribute;

class Collection  extends \Magento\Eav\Model\ResourceModel\Attribute\Collection
{
    /**
     * Default attribute entity type code
     *
     * @var string
     */
    protected $_entityTypeCode = 'merchant';

    /**
     * @inheritDoc
     */
    protected function _getEntityTypeCode()
    {
        return $this->_entityTypeCode;
    }

    /**
     * @inheritDoc
     */
    protected function _getEavWebsiteTable()
    {
        return $this->getTable('merchant_eav_attribute_website');
    }
}
