<?php

namespace Magenest\DatabaseConnection\Model;

use Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface;
use Magento\Framework\Model\AbstractModel;

class CustomerTraining extends AbstractModel implements CustomerTrainingInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel\CustomerTraining::class);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }


    /**
     * @inheritDoc
     */
    public function getFirstName()
    {
        return parent::getData(self::FIRST_NAME);
    }

    /**
     * @inheritDoc
     */
    public function getLastName()
    {
        return parent::getData(self::LAST_NAME);
    }

    /**
     * @inheritDoc
     */
    public function getAddress()
    {
        return parent::getData(self::ADDRESS);
    }

    /**
     * @inheritDoc
     */
    public function getCity()
    {
        return parent::getData(self::CITY);
    }

    /**
     * @inheritDoc
     */
    public function getAge()
    {
        return parent::getData(self::AGE);
    }

    /**
     * @inheritDoc
     */
    public function setFirstName($firstName)
    {
        return $this->setData(self::FIRST_NAME, $firstName);
    }

    /**
     * @inheritDoc
     */
    public function setLastName($lastName)
    {
        return $this->setData(self::LAST_NAME, $lastName);
    }

    /**
     * @inheritDoc
     */
    public function setAddress($address)
    {
        return $this->setData(self::ADDRESS, $address);
    }

    /**
     * @inheritDoc
     */
    public function setCity($city)
    {
        return $this->setData(self::CITY, $city);
    }

    /**
     * @inheritDoc
     */
    public function setAge($age)
    {
        return $this->setData(self::AGE, $age);
    }
}
