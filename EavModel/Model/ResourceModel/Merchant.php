<?php

namespace Magenest\EavModel\Model\ResourceModel;

class Merchant extends \Magento\Eav\Model\Entity\AbstractEntity
{
    /**
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Magento\Eav\Model\Entity\Type
     */
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\Magenest\EavModel\Model\Merchant::ENTITY);
        }
        return parent::getEntityType();
    }
}
