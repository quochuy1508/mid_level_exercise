<?php

namespace Magenest\LayoutHandling\Plugin\Controller\Product;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\Page as ResultPage;

class View
{
    const LAYOUT_ADDITIONAL_PREFIX_NAME = 'catalog_product_view_price_';

    /**
     * Catalog product
     *
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Magento\Catalog\Helper\Product\View $subject
     * @param ResultPage $resultPage
     * @param int $productId
     * @param Action $controller
     * @param null|DataObject $params
     * @return array
     * @throws NoSuchEntityException
     */
    public function beforePrepareAndRender(
        \Magento\Catalog\Helper\Product\View $subject,
        ResultPage $resultPage,
        int $productId,
        Action $controller,
        $params = null
    ) {
        if ($controller instanceof \Magento\Catalog\Controller\Product\View) {
            $resultPage->addHandle($this->getNameAdditionalLayoutForPriceProduct($productId));
        }
        return [$resultPage, $productId, $controller, $params];
    }

    /**
     * @param int $productId
     * @return string
     * @throws NoSuchEntityException
     */
    private function getNameAdditionalLayoutForPriceProduct(int $productId): string
    {
        $product = $this->productRepository->getById($productId);
        $specialPrice = $product->getFinalPrice();
        switch ($specialPrice) {
            case $specialPrice >= 250 :
                return self::LAYOUT_ADDITIONAL_PREFIX_NAME . '250';
            case $specialPrice >= 192 :
                return self::LAYOUT_ADDITIONAL_PREFIX_NAME . '190_250';
            case $specialPrice >= 120 :
                return self::LAYOUT_ADDITIONAL_PREFIX_NAME . '120_190';
            case $specialPrice >= 50 :
                return self::LAYOUT_ADDITIONAL_PREFIX_NAME . '50_120';
            default:
                return self::LAYOUT_ADDITIONAL_PREFIX_NAME . '0_50';
        }
    }
}
