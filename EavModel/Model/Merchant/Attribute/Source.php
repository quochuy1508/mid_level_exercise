<?php

namespace Magenest\EavModel\Model\Merchant\Attribute;

use Magenest\EavModel\Model\ResourceModel\Merchant\Collection;
use Magenest\EavModel\Model\ResourceModel\Merchant\CollectionFactory;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

class Source extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    protected $merchantCollection;

    /**
     *
     */
    public function __construct(CollectionFactory $merchantCollection)
    {
        $this->merchantCollection = $merchantCollection;
    }

    /**
     * @inheritDoc
     */
    public function getAllOptions()
    {
        $result = [];

        /**
         * @var $collection Collection
         */
        $collection = $this->merchantCollection->create();
        $collection->addAttributeToFilter('merchant_status', 1);

        foreach ($collection->getItems() as $item) {
            $result[] = ['value' => $item->getEntityId(), 'label' => $item->getStoreName()];
        }

        return $result;
    }
}
