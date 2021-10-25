<?php

namespace Magenest\CustomRouter\Plugin;

use Magento\Catalog\Model\Layer\Filter\Item;

class ChangeUrlFilterPrice
{
    /**
     * @param Item $subject
     * @param $result
     * @return mixed
     */
    public function afterGetUrl(Item $subject, $result)
    {
        $pathInfo = $result;
        $patternOnlyPriceNumber = '/\d+(\.\d{1,2})?-\d+(\.\d{1,2})?/';
        $patternPrice = '/\?price=\d+(\.\d{1,2})?-\d+(\.\d{1,2})?/';
        if (
            preg_match($patternOnlyPriceNumber, $pathInfo, $matchOnlyPrice) &&
            preg_match($patternPrice, $pathInfo, $matchAllParam)
        ) {
            $pathNotParams = preg_replace($patternPrice, '', $pathInfo);
            return substr_replace($pathNotParams, '-price-'.$matchOnlyPrice[0], strlen($pathNotParams) - 5, 0);
        } else {
            return $result;
        }
    }
}
