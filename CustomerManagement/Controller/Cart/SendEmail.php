<?php

namespace Magenest\CustomerManagement\Controller\Cart;

use Exception;
use Magenest\CustomerManagement\Model\ResourceModel\SavedCart as SavedCartResourceModel;
use Magenest\CustomerManagement\Model\SavedCart;
use Magenest\CustomerManagement\Model\SavedCartFactory;
use Magento\Checkout\Model\Session;
use Magento\Contact\Model\ConfigInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Area;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;

class SendEmail extends Action
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

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var ConfigInterface
     */
    private $contactsConfig;

    public function __construct(
        Context                         $context,
        Json                            $json,
        JsonFactory                     $resultJsonFactory,
        Session                         $session,
        \Magento\Customer\Model\Session $customerSession,
        SavedCartResourceModel          $savedCartResourceModel,
        SavedCartFactory                $savedCart,
        StateInterface                  $inlineTranslation,
        TransportBuilder                $transportBuilder,
        StoreManagerInterface           $storeManager,
        ConfigInterface                 $contactsConfig
    ) {
        $this->json = $json;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->session = $session;
        $this->customerSession = $customerSession;
        $this->savedCartResourceModel = $savedCartResourceModel;
        $this->savedCart = $savedCart;
        $this->inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        $this->contactsConfig = $contactsConfig;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            if ($this->customerSession->isLoggedIn()) {
                $quoteId = $this->getRequest()->getParam('id');

                if ($quoteId) {
                    $customerData = $this->customerSession->getCustomerData();
                    $this->send(
                        [
                            'customerName' => $this->customerSession->getName(),
                            'savedCartLink' => $this->_url->getUrl('customermanagement/cart/view', ['customer_email', $customerData->getEmail(), 'quote_id' => $quoteId])
                        ],
                        $customerData->getEmail()
                    );
                    $this->messageManager->addSuccessMessage(__("Send email successfully"));
                } else {
                    $this->messageManager->addErrorMessage(__("Send email failure"));
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

    /**
     * Send email from contact form
     *
     * @param array $variables
     * @return void
     */
    public function send(array $variables, $toEmail)
    {
        $this->inlineTranslation->suspend();
        try {
            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('saved_cart_email_template')
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => $this->_storeManager->getStore()->getId()
                    ]
                )
                ->setTemplateVars($variables)
                ->setFromByScope($this->contactsConfig->emailSender())
                ->addTo($toEmail, 'asdasdasd')
                ->getTransport();

            $transport->sendMessage();
        } finally {
            $this->inlineTranslation->resume();
        }
    }
}
