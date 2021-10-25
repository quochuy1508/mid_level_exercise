<?php

namespace Magenest\FullPageCache\Block;

use Magenest\FullPageCache\Observer\CustomerGenderContext;
use Magento\Customer\Model\Context;
use Magento\Framework\View\Element\Template;

class CustomerGender extends Template
{
    protected $httpContext;
    protected $customerSession;

    public function __construct(
        Template\Context                    $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Customer\Model\Session $customerSession,
        array                               $data = []
    ) {
        $this->httpContext = $httpContext;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getCustomerIsLoggedIn()
    {
        return (bool)$this->httpContext->getValue(Context::CONTEXT_AUTH);
    }

    public function getCustomerGender()
    {
        return $this->httpContext->getValue(CustomerGenderContext::CONTEXT_GENDER);
    }
}
