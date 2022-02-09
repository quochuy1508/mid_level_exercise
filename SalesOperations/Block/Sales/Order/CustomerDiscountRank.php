<?php

namespace Magenest\SalesOperations\Block\Sales\Order;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\Order;
use Magento\Tax\Model\Config;

class CustomerDiscountRank extends Template
{
    /**
     * Tax configuration model
     *
     * @var Config
     */
    protected $_config;

    /**
     * @var Order
     */
    protected $_order;

    /**
     * @var DataObject
     */
    protected $_source;

    /**
     * @param Context $context
     * @param Config $taxConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config  $taxConfig,
        array   $data = []
    ) {
        $this->_config = $taxConfig;
        parent::__construct($context, $data);
    }

    /**
     * Check if we nedd display full tax total info
     *
     * @return bool
     */
    public function displayFullSummary()
    {
        return true;
    }

    /**
     * @return array
     */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

    /**
     * @return array
     */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }

    /**
     * Initialize all order totals relates with tax
     *
     * @return $this
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_order = $parent->getOrder();
        $this->_source = $parent->getSource();
        $store = $this->getStore();
        if ((int)$this->_order->getCustomerRankDiscount() !== 0) {
            $customerDiscountRank = new DataObject(
                [
                    'code' => 'customer_rank_discount',
                    'strong' => false,
                    'value' => $this->_order->getCustomerRankDiscount(),
                    'label' => __('Customer Rank Discount'),
                ]
            );

            $parent->addTotal($customerDiscountRank, 'customer_rank_discount');
        }

        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * Get data (totals) source model
     *
     * @return DataObject
     */
    public function getSource()
    {
        return $this->_source;
    }

    public function getStore()
    {
        return $this->_order->getStore();
    }
}
