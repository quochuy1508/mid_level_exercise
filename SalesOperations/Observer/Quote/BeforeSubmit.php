<?php

namespace Magenest\SalesOperations\Observer\Quote;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;


/**
 * Class BeforeSubmit
 * @package Magenest\Wrapper\Observer\Quote
 */
class BeforeSubmit implements ObserverInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getOrder();
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getQuote();

        $order->setCustomerRankDiscount($quote->getCustomerRankDiscount());
        $order->setBaseCustomerRankDiscount($quote->getBaseCustomerRankDiscount());

        return $this;
    }
}
