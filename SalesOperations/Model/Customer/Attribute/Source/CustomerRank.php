<?php

namespace Magenest\SalesOperations\Model\Customer\Attribute\Source;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magenest\SalesOperations\Helper\Data;

class CustomerRank extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var Data
     */
    private $dataHelper;

    /**
     * @param Data $dataHelper
     */
    public function __construct(
        Data $dataHelper
    ) {
        $this->dataHelper = $dataHelper;
    }

    /**
     * @inheritDoc
     */
    public function getAllOptions()
    {
        $options = $this->dataHelper->getCustomerRankData();
        array_unshift($options, ['value' => '', 'label' => __('Select Customer Rank')]);
        return $options;
    }
}
