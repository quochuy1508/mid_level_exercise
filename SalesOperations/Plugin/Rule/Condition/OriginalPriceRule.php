<?php

namespace Magenest\SalesOperations\Plugin\Rule\Condition;

use Magenest\SalesOperations\Model\Rule\Condition\OriginalPrice;
use Magento\Rule\Model\Condition\AbstractCondition;

class OriginalPriceRule
{
    /**
     * @param AbstractCondition $subject
     * @param $result
     * @return array
     */
    public function afterGetNewChildSelectOptions(AbstractCondition $subject, $result)
    {
        $valueInsert = [
            'label' => __('Original Price'),
            'value' => OriginalPrice::class
        ];

        array_unshift($result, $valueInsert);

        return $result;
    }
}
