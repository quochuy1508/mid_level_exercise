<?php

namespace Magenest\SalesOperations\Block\Adminhtml\Order;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Sales\Block\Adminhtml\Order\AbstractOrder;
use Magento\Sales\Helper\Admin;
use Magento\Shipping\Helper\Data as ShippingHelper;
use Magento\Tax\Helper\Data as TaxHelper;
use Magento\User\Model\UserFactory;

class ExtraInformation extends AbstractOrder
{
    /**
     * User model factory
     *
     * @var UserFactory
     */
    protected $_userFactory;

    public function __construct(
        Context         $context,
        Registry        $registry,
        Admin           $adminHelper,
        UserFactory     $_userFactory,
        array           $data = [],
        ?ShippingHelper $shippingHelper = null,
        ?TaxHelper      $taxHelper = null
    ) {
        $this->_userFactory = $_userFactory;
        parent::__construct(
            $context,
            $registry,
            $adminHelper,
            $data,
            $shippingHelper,
            $taxHelper
        );
    }

    /**
     * @return null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSaleAgentInformation()
    {
        if ($this->getOrder() && $this->getOrder()->getSaleAgentId()) {
            /** @var $model \Magento\User\Model\User */
            $model = $this->_userFactory->create()->load($this->getOrder()->getSaleAgentId());
            return $model->getName();
        }

        return null;
    }

    /**
     * @return null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getOrderSourceInformation()
    {
        return $this->getOrder() ? $this->getOrder()->getOrderSource() : null;
    }
}
