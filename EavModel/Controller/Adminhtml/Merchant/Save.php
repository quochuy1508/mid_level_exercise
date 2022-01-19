<?php

namespace Magenest\EavModel\Controller\Adminhtml\Merchant;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Magenest\EavModel\Model\Merchant;
use Magenest\EavModel\Model\MerchantFactory;
use Magenest\EavModel\Model\ResourceModel\Merchant as MerchantResourceModel;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;

/**
 * Save Merchant action.
 */
class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var MerchantFactory
     */
    private $merchantFactory;

    /**
     * @var MerchantResourceModel
     */
    private $merchantResourceModel;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     * @param MerchantFactory|null $merchantFactory
     * @param MerchantResourceModel|null $merchantResourceModel
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        DataPersistorInterface $dataPersistor,
        MerchantFactory $merchantFactory = null,
        MerchantResourceModel $merchantResourceModel = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->merchantFactory = $merchantFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(MerchantFactory::class);
        $this->merchantResourceModel = $merchantResourceModel
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(MerchantResourceModel::class);
        parent::__construct($context, $coreRegistry);
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
            if (isset($data['category_ids']) && is_array($data['category_ids'])) {
                $data['category_ids'] = implode(',', array_values($data['category_ids']));
            }
            /** @var Merchant $model */
            $model = $this->merchantFactory->create();

            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                try {
                    $this->merchantResourceModel->load($model, $id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This merchant no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->merchantResourceModel->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the merchant.'));
                $this->dataPersistor->clear('merchant');
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the merchant.'));
            }

            $this->dataPersistor->set('merchant', $data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
