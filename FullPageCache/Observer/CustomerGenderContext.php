<?php

namespace Magenest\FullPageCache\Observer;

use Magento\Customer\Api\Data\CustomerInterface;

use Magento\Framework\Event\ObserverInterface;

class CustomerGenderContext implements ObserverInterface
{
    const CONTEXT_GENDER = 'customer_gender';

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\Http\Context $httpContext
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Http\Context $httpContext
    ) {
        $this->customerSession = $customerSession;
        $this->httpContext = $httpContext;
    }

    /**
     * \Magento\Framework\App\Http\Context::getVaryString is used by Magento to retrieve unique identifier for selected context,
     * so this is a best place to declare custom context variables
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customerData = $this->customerSession->getCustomerData();
        if ($customerData instanceof CustomerInterface) {
            $defaultGenderContext = 'female';
            $genderContext = $customerData->getGender() == 1 ? 'male' : $defaultGenderContext;
            $this->httpContext->setValue(self::CONTEXT_GENDER, $genderContext, $defaultGenderContext);
        }
    }
}
