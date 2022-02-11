<?php

namespace Magenest\CustomerManagement\Controller\Cart;

use Exception;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Model\QuoteRepository;

class Restore extends Action
{
    /**
     * @var Json
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
     * @var QuoteRepository
     */
    protected $quoteRepository;

    public function __construct(
        Context                                      $context,
        Json $json,
        Session                                      $session,
        \Magento\Customer\Model\Session              $customerSession,
        QuoteRepository                              $quoteRepository
    ) {
        $this->json = $json;
        $this->session = $session;
        $this->customerSession = $customerSession;
        $this->quoteRepository = $quoteRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        try {
            if ($this->customerSession->isLoggedIn()) {
                $quoteRestoreId = $this->getRequest()->getParam('quote_id');
                $quoteRestore = $this->quoteRepository->get($quoteRestoreId);
                $quoteRestore->setIsActive(1);
                $this->quoteRepository->save($quoteRestore);
                $this->session->replaceQuote($quoteRestore);
                $this->messageManager->addSuccessMessage(__("Restore quote successfully"));
                $resultRedirect->setPath('checkout/cart');
            }
        } catch (Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            $resultRedirect->setPath('*/*');
        }
        return $resultRedirect;
    }
}
