<?php

namespace Magenest\EavModel\Model\Merchant\Form;

use Magento\Framework\Data\OptionSourceInterface;

class MerchantStatus implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value'         => 1,
                "label"         => "Active",
            ],
            [
                'value'         => 2,
                "label"         => "Pending",
            ],
            [
                'value'         => 3,
                "label"         => "Blocked",
            ],
            [
                'value'         => 4,
                "label"         => "Rejected",
            ]
        ];
    }
}
