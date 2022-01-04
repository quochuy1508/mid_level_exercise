<?php

namespace Magenest\EavType\Model\ResourceModel;

use Magento\Eav\Model\Entity\AbstractEntity;

class Merchant extends AbstractEntity
{
    /**
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Magento\Eav\Model\Entity\Type
     */
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType('merchant');
        }
        return parent::getEntityType();
    }
}
