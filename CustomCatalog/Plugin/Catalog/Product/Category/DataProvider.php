<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magenest\CustomCatalog\Plugin\Catalog\Product\Category;

use \Magento\Catalog\Ui\DataProvider\Product\Form\NewCategoryDataProvider;

class DataProvider
{
    /**
     * @param NewCategoryDataProvider $subject
     * @param array $result
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetData(NewCategoryDataProvider $subject, $result)
    {
        return array_replace_recursive(
            $result,
            [
                'config' => [
                    'data' => [
                        'use_as_main_breadcrumb' => 0,
                    ]
                ]
            ]
        );
    }
}
