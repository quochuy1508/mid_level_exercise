<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magenest\DatabaseConnection\Controller\Adminhtml\Customer;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magenest\DatabaseConnection\Api\CustomerTrainingRepositoryInterface;
use Magenest\DatabaseConnection\Model\CustomerTraining;
use Magenest\DatabaseConnection\Model\CustomerTrainingFactory;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Save CMS customerTraining action.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magenest_DatabaseConnection::save';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var CustomerTrainingFactory
     */
    private $customerTrainingFactory;

    /**
     * @var CustomerTrainingRepositoryInterface
     */
    private $customerTrainingRepository;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param CustomerTrainingFactory|null $customerTrainingFactory
     * @param CustomerTrainingRepositoryInterface|null $customerTrainingRepository
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        CustomerTrainingFactory $customerTrainingFactory = null,
        CustomerTrainingRepositoryInterface $customerTrainingRepository = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->customerTrainingFactory = $customerTrainingFactory ?: ObjectManager::getInstance()->get(CustomerTrainingFactory::class);
        $this->customerTrainingRepository = $customerTrainingRepository ?: ObjectManager::getInstance()->get(CustomerTrainingRepositoryInterface::class);
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {

            /** @var CustomerTraining $model */
            $model = $this->customerTrainingFactory->create();

            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                try {
                    $model = $this->customerTrainingRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This customerTraining no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            } else {
                unset($data['entity_id']);
            }

            $model->setData($data);

            try {
                $this->_eventManager->dispatch(
                    'customer_training_prepare_save',
                    ['customerTraining' => $model, 'request' => $this->getRequest()]
                );

                $this->customerTrainingRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the customer.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (\Throwable $e) {
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the customer.'));
            }

            $this->dataPersistor->set('customer_training', $data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
