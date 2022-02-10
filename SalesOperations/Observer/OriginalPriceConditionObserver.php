<?php

namespace Magenest\SalesOperations\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magenest\SalesOperations\Model\Rule\Condition\OriginalPrice;

class OriginalPriceConditionObserver implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        $additional = $observer->getAdditional();
        $conditions = (array)$additional->getConditions();

        $conditions = array_merge_recursive($conditions, [
            $this->getCustomerFirstOrderCondition()
        ]);

        $additional->setConditions($conditions);
        return $this;
    }

    private function getCustomerFirstOrderCondition()
    {
        return [
            'label' => __('Original Price'),
            'value' => OriginalPrice::class
        ];
    }
}
