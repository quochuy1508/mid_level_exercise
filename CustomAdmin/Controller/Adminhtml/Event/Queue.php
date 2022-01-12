<?php

namespace Magenest\CustomAdmin\Controller\Adminhtml\Event;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magenest\CustomAdmin\Api\Data\OperationInterface;
use Magenest\CustomAdmin\Api\Data\OperationInterfaceFactory;
use Magento\Customer\Model\ResourceModel\Customer\Collection;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;

class Queue extends Action
{
    const SIZE = 1000;

    /**
     * @var string
     */
    const TOPIC_NAME = 'event_customer.register';

    /**
     * @var PublisherInterface
     */
    protected $publisher;

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * @var OperationInterfaceFactory
     */
    protected $operation;

    /**
     * @var CollectionFactory
     */
    protected $customerCollection;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param PublisherInterface $publisher
     * @param OperationInterfaceFactory $operation
     * @param CollectionFactory $customerCollection
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        PublisherInterface $publisher,
        OperationInterfaceFactory $operation,
        CollectionFactory $customerCollection,
        SerializerInterface $serializer
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->publisher = $publisher;
        $this->operation = $operation;
        $this->customerCollection = $customerCollection;
        $this->serializer = $serializer;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->_resultJsonFactory->create();
        $postData = $this->getRequest()->getPostValue();

        /**
         * @var OperationInterface $operation
         */
        $operation = $this->operation->create();
        $operation->setData($postData);

        $customerCollection = $this->customerCollection->create();
        $customerIds = $customerCollection->getAllIds();

        $chunks = array_chunk($customerIds,self::SIZE);
        foreach ($chunks as $chunk) {
            //publish IDs to queue
            $operation->setCustomerIds($this->serializer->serialize($chunk));
            $this->publisher->publish(self::TOPIC_NAME, $operation);
        }

        $result->setData(['output' => 'ok']);
        return $result;
    }
}
