<?php

namespace Magenest\DeliveryDate\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;

class SalesModelServiceQuoteSubmitBefore implements ObserverInterface
{
    /**
     * @param EventObserver $observer
     * @return $this
     * @throws \Exception
     */
    public function execute(EventObserver $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();

        $order->setDeliveryDate($quote->getDeliveryDate());
        $order->setDeliveryComment($quote->getDeliveryComment());

        return $this;
    }
}
