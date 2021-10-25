<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magenest\FullPageCache\Plugin\ConfigurableProduct\Block\Product\View\Type;

use Magento\ConfigurableProduct\Block\Product\View\Type\Configurable as Subject;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\InventorySales\Model\ResourceModel\GetAssignedStockIdForWebsite;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class for adding info about qty of product
 */
class AddAdditionalInfo
{
    /**
     * @var Json
     */
    private $jsonSerializer;

    /**
     * @var GetProductSalableQtyInterface
     */
    private $getProductSalableQty;

    /**
     * @var GetAssignedStockIdForWebsite
     */
    private $getAssignedStockIdForWebsite;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param Json $jsonSerializer
     * @param GetAssignedStockIdForWebsite $getAssignedStockIdForWebsite
     * @param GetProductSalableQtyInterface $getProductSalableQty
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Json $jsonSerializer,
        GetAssignedStockIdForWebsite $getAssignedStockIdForWebsite,
        GetProductSalableQtyInterface $getProductSalableQty,
        StoreManagerInterface $storeManager
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->getAssignedStockIdForWebsite = $getAssignedStockIdForWebsite;
        $this->getProductSalableQty = $getProductSalableQty;
        $this->storeManager = $storeManager;
    }

    /**
     * Add data about qty product for config
     *
     * @param Subject $configurable
     * @param string $result
     * @return string
     */
    public function afterGetJsonConfig(Subject $configurable, string $result): string
    {
        $jsonConfig = $this->jsonSerializer->unserialize($result);
        $stockId = $this->getAssignedStockIdForWebsite->execute($this->storeManager->getWebsite()->getCode());
        $jsonConfig['qty'] = $this->getProductVariationsQty($configurable, $stockId);

        return $this->jsonSerializer->serialize($jsonConfig);
    }

    /**
     * Get product variations qty.
     *
     * @param Subject $configurable
     * @param int $stockId
     * @return array
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getProductVariationsQty(Subject $configurable, int $stockId): array
    {
        $qtys = [];
        foreach ($configurable->getAllowProducts() as $product) {
            $qtys[$product->getId()] = $this->getProductSalableQty->execute($product->getSku(), $stockId);
        }

        return $qtys;
    }
}
