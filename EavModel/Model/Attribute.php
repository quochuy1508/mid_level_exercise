<?php

namespace Magenest\EavModel\Model;

class Attribute extends \Magento\Eav\Model\Attribute
{
    /**
     * Name of the module
     */
    const MODULE_NAME = 'Magenest_EavModel';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'merchant_eav_attribute';

    /**
     * Prefix of model events object
     *
     * @var string
     */
    protected $_eventObject = 'attribute';

    /**
     * Init resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Magenest\EavModel\Model\ResourceModel\Attribute::class);
    }
}
