<?php

namespace Magenest\CustomAdmin\Controller\Adminhtml\Event;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;


class Queue extends Action
{

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;


    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }


    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $result = $this->_resultJsonFactory->create();
        $resultPage = $this->_resultPageFactory->create();
        $currentProductId = $this->getRequest()->getPostValue();

        $result->setData(['output' => 'ok']);
        return $result;
    }

}
