<?php

namespace Magenest\Cache\Controller\Adminhtml\Index;

use Magenest\Cache\Model\ResourceModel\CustomerDirectory as CustomerDirectoryResourceModel;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magenest\Cache\Model\CustomerDirectoryFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

/**
 * Edit CMS block action.
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var CustomerDirectoryFactory
     */
    protected $customerDirectoryFactory;

    /**
     * @var CustomerDirectoryResourceModel
     */
    private $resourceModel;

    /**
     * @var Registry
     */
    private $_coreRegistry;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param CustomerDirectoryFactory $customerDirectoryFactory
     * @param CustomerDirectoryResourceModel $resourceModel
     */
    public function __construct(
        Context                  $context,
        Registry                 $coreRegistry,
        PageFactory              $resultPageFactory,
        CustomerDirectoryFactory $customerDirectoryFactory,
        CustomerDirectoryResourceModel $resourceModel
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->customerDirectoryFactory = $customerDirectoryFactory;
        $this->resourceModel = $resourceModel;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Edit CMS block
     *
     * @return ResultInterface
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $model = $this->customerDirectoryFactory->create();

        // 2. Initial checking
        if ($id) {
            $this->resourceModel->load($model, $id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This block no longer exists.'));
                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('customer_directory', $model);

        // 5. Build edit form
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Customer Directory') : __('New Customer Directory'),
            $id ? __('Edit Customer Directory') : __('New Customer Directory')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Customer Directorys'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Customer Directory'));
        return $resultPage;
    }
}
