<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magenest\EavModel\Model\Adapter\BatchDataMapper;

use Magento\AdvancedSearch\Model\Adapter\DataMapper\AdditionalFieldsProviderInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Provide data mapping for custom fields
 */
class MerchantDataProvider implements AdditionalFieldsProviderInterface
{
    const MERCHANT_ID = 'merchant_id';
    const MERCHANT_NAME = 'merchant_name';

    protected $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @inheritdoc
     */
    public function getFields(array $productIds, $storeId)
    {
        $fields = [];

        foreach ($productIds as $productId) {
            $product = $this->productRepository->getById($productId);

            $fields[$productId] = [self::MERCHANT_ID => $product->getData(self::MERCHANT_ID) ?? 0];
            $fields[$productId] = [self::MERCHANT_NAME => $product->getData(self::MERCHANT_ID) ?? 0];
        }
        return $fields;
    }
}
