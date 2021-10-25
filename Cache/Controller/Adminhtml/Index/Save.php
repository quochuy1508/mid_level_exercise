<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\Cache\Controller\Adminhtml\Index;

use Magenest\Cache\Model\CustomerDirectory;
use Magenest\Cache\Model\ResourceModel\CustomerDirectory as CustomerDirectoryResourceModel;
use Magenest\Cache\Model\CustomerDirectoryFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;

/**
 * Save CMS customerDirectory action.
 */
class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var CustomerDirectoryFactory
     */
    private $customerDirectoryFactory;

    /**
     * @var CustomerDirectoryResourceModel
     */
    private $resourceModel;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param CustomerDirectoryResourceModel $resourceModel
     * @param CustomerDirectoryFactory|null $customerDirectoryFactory
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        CustomerDirectoryResourceModel $resourceModel,
        CustomerDirectoryFactory $customerDirectoryFactory = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->resourceModel = $resourceModel;
        $this->customerDirectoryFactory = $customerDirectoryFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(CustomerDirectoryFactory::class);
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }

            /** @var CustomerDirectory $model */
            $model = $this->customerDirectoryFactory->create();

            $id = $this->getRequest()->getParam('id');
            if ($id) {
                try {
                    $this->resourceModel->load($model, $id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This customerDirectory no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);
            try {
                $this->resourceModel->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the customerDirectory.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the customerDirectory.'));
            }

//            $this->dataPersistor->set('cms_customerDirectory', $data);
//            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

}
