<?php

namespace Magenest\CustomerManagement\Controller\Recently\Viewed;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;

class Info extends Action
{
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $json;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    public function __construct(
        Context            $context,
        \Magento\Framework\Serialize\Serializer\Json     $json,
        JsonFactory $resultJsonFactory,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->json = $json;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        $result = [];
        try {
            //lấy dữ liệu từ ajax gửi sang
            $dataProductIds = $this->getRequest()->getParam('dataProduct');

            $productIds = $this->json->unserialize($dataProductIds);

            $searchCriteria = $this->searchCriteriaBuilder->addFilter(
                'entity_id',
                $productIds,
                'in'
            )->create();

            $products = $this->productRepository->getList($searchCriteria)->getItems();

            foreach ($products as $product) {
                $result[] = [
                    'url' => $product->getProductUrl(),
                    'name' => $product->getName()
                ];
            }

        } catch (\Exception $exception) {

        }

        // chuyển kết quả về dạng object json và trả về cho ajax
        return $resultJson->setData($this->json->serialize($result));
    }
}
