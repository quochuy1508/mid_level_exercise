<?php

namespace Magenest\CustomCheckout\Controller\Validate;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Class DeliveryTime
 */
class DeliveryTime extends Action
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
     * @param Context $context
     * @param \Magento\Framework\Serialize\Serializer\Json $json
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context                                      $context,
        \Magento\Framework\Serialize\Serializer\Json $json,
        JsonFactory                                  $resultJsonFactory
    ) {
        $this->json = $json;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        $response = [
            'success' => true
        ];

        $deliveryTime = $this->getRequest()->getParam('delivery_time');
        if ($deliveryTime && !preg_match('/^((?:[01]\d|2[0-3])h-(?:\s?)(?:[01]\d|2[0-3])h)+$/', $deliveryTime)) {
            $response['success'] = false;
            $response['message'] = __('Delivery Time Must follow by format **h-**h');
        }

        // chuyển kết quả về dạng object json và trả về cho ajax
        return $resultJson->setData($response);
    }
}
