<?php

namespace Magenest\SalesOperations\Model\Total\Quote;

use Magenest\SalesOperations\Helper\Data;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;

/**
 * Class CustomerRankDiscount
 */
class CustomerRankDiscount extends AbstractTotal
{
    /**
     * @var PriceCurrencyInterface
     */
    protected $_priceCurrency;

    /**
     * @var Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Quote\Model\QuoteValidator
     */
    private $quoteValidator;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param Data $dataHelper
     * @param \Magento\Quote\Model\QuoteValidator $quoteValidator
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        Data $dataHelper,
        \Magento\Quote\Model\QuoteValidator $quoteValidator
    ) {
        $this->_priceCurrency = $priceCurrency;
        $this->dataHelper = $dataHelper;
        $this->quoteValidator = $quoteValidator;
    }

    public function collect(
        Quote                       $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total                       $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $customerRankDiscount = 0;
        $customer = $quote->getCustomer();
        if ($customer->getId() && ($customerRankAttribute = $customer->getCustomAttribute('customer_rank'))) {
            $value = $customerRankAttribute->getValue();
            $options = $this->dataHelper->getCustomerRankDataRaw();
            if (isset($options[$value])) {
                $customerRankDiscount = $this->handleDiscount($quote->getSubtotal(), $options[$value]['discount']);
            }
        }

        if ($customerRankDiscount != 0) {
            $total->setTotalAmount('customer_rank_discount', $customerRankDiscount);
            $total->setBaseTotalAmount('customer_rank_discount', $customerRankDiscount);
            $total->setCustomerRankDiscount($customerRankDiscount);
            $total->setBaseCustomerRankDiscount($customerRankDiscount);
            $total->setGrandTotal($total->getGrandTotal());
            $total->setBaseGrandTotal($total->getBaseGrandTotal());

            $quote->setCustomerRankDiscount($customerRankDiscount);
            $quote->setBaseCustomerRankDiscount($customerRankDiscount);
        }

        return $this;
    }

    /**
     * Assign subtotal amount and label to address object
     *
     * @param Quote $quote
     * @param Total $total
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetch(Quote $quote, Total $total)
    {
        $result = null;
        $amount = $total->getCustomerRankDiscount();

        if (!$amount) {
            $amount = $quote->getCustomerRankDiscount();
        }

        if ($amount != 0) {
            $result = [
                'code' => 'customer_rank_discount',
                'title' => $this->getLabel(),
                'value' => $amount
            ];
        }

        return $result;
    }

    /**
     * get label
     * @return string
     */
    public function getLabel()
    {
        return __('Customer Rank Discount');
    }

    /**
     * @param $subTotal
     * @param $discount
     * @return float|int
     */
    private function handleDiscount($subTotal, $discount)
    {
        if (strpos($discount, '%') !== false) {
            return -(float)trim($discount, '%') / 100 * $subTotal;
        } else {
            return -$discount;
        }
    }

    protected function clearValues(Total $total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);
    }
}
