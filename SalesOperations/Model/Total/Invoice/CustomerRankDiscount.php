<?php

namespace Magenest\SalesOperations\Model\Total\Invoice;

/**
 * Class CustomerRankDiscount
 */
class CustomerRankDiscount extends \Magento\Sales\Model\Order\Invoice\Total\AbstractTotal
{
    /**
     * Constructor
     *
     * By default is looking for first argument as array and assigns it as object
     * attributes This behavior may change in child classes
     *
     * @param array $data
     */
    public function __construct(
        array $data = []
    ) {
        parent::__construct($data);
    }

    /**
     * Collect Weee amounts for the invoice
     *
     * @param  \Magento\Sales\Model\Order\Invoice $invoice
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
    {
        $store = $invoice->getStore();
        $order = $invoice->getOrder();

        $invoice->setGrandTotal($invoice->getGrandTotal() + $order->getCustomerRankDiscount());
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $order->getBaseCustomerRankDiscount());
        $invoice->setCustomerRankDiscount($order->getCustomerRankDiscount());
        $invoice->setBaseCustomerRankDiscount($order->getBaseCustomerRankDiscount());
        return $this;
    }
}
