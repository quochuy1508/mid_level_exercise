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

class Delete extends Action
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
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            if ($this->customerSession->isLoggedIn()) {
                $quoteId = $this->getRequest()->getParam('quote_id');

                /**
                 * @var SavedCart $savedCartModel
                 */
                $savedCartModel = $this->savedCart->create();
                if ($quoteId) {
                    $this->savedCartResourceModel->load($savedCartModel, $quoteId);
                    $this->savedCartResourceModel->delete($savedCartModel);
                    $this->messageManager->addSuccessMessage(__("Delete saved quote successfully"));
                } else {
                    $this->messageManager->addErrorMessage(__("Delete saved quote failure"));
                }

            } else {
                $this->messageManager->addErrorMessage(__("Please login to action request"));
            }
        } catch (Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }

        $resultRedirect->setPath('*/*');
        return $resultRedirect;
    }
}
