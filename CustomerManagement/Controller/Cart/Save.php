<?php

namespace Magenest\CustomerManagement\Controller\Cart;

use Exception;
use Magenest\CustomerManagement\Model\ResourceModel\SavedCart as SavedCartResourceModel;
use Magenest\CustomerManagement\Model\SavedCartFactory;
use Magenest\CustomerManagement\Model\SavedCart;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class Save extends Action
{
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $json;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var SavedCartResourceModel
     */
    protected $savedCartResourceModel;

    /**
     * @var SavedCartFactory
     */
    protected $savedCart;

    public function __construct(
        Context                                      $context,
        \Magento\Framework\Serialize\Serializer\Json $json,
        JsonFactory                                  $resultJsonFactory,
        Session                                      $session,
        \Magento\Customer\Model\Session              $customerSession,
        SavedCartResourceModel                       $savedCartResourceModel,
        SavedCartFactory                             $savedCart
    ) {
        $this->json = $json;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->session = $session;
        $this->customerSession = $customerSession;
        $this->savedCartResourceModel = $savedCartResourceModel;
        $this->savedCart = $savedCart;
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        $result = ['success' => false];
        try {
            if ($this->customerSession->isLoggedIn()) {
                $quoteId = $this->session->getQuoteId();
                $customerId = $this->customerSession->getCustomerId();
                /**
                 * @var SavedCart $savedCartModel
                 */
                $savedCartModel = $this->savedCart->create();
                $select = $this->savedCartResourceModel->getConnection()->select();

                $select->from($this->savedCartResourceModel->getMainTable())
                    ->where('quote_id = ?', $quoteId)
                    ->where('customer_id = ?', $customerId);

                $data = $this->savedCartResourceModel->getConnection()->fetchRow($select);
                if ($data && $data['entity_id']) {
                    $this->savedCartResourceModel->load($savedCartModel, $data['entity_id']);
                }

                $savedCartModel->setData('quote_id', $quoteId);
                $savedCartModel->setData('customer_id', $customerId);
                $this->savedCartResourceModel->save($savedCartModel);
                $result['success'] = true;
                $this->messageManager->addSuccessMessage(__("Saved Quote Successfully"));
            }
        } catch (Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        return $resultJson->setData($result);
    }
}
