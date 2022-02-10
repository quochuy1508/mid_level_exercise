<?php

namespace Magenest\SalesOperations\Plugin\Adminhtml;

use Magenest\SalesOperations\Helper\Data;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Sales\Block\Adminhtml\Order\View;
use Magento\Sales\Model\Order;
use Magento\Framework\View\LayoutInterface;

class ViewOrder
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var Data */
    private $dataHelper;

    /** @var Registry */
    private $registry;

    /**
     * @param Registry $registry
     * @param Data $dataHelper
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Registry             $registry,
        Data $dataHelper,
        SerializerInterface  $serializer
    ) {
        $this->registry = $registry;
        $this->serializer = $serializer;
        $this->dataHelper = $dataHelper;
    }

    /**
     * Before set layout
     *
     * @param View $subject
     * @param LayoutInterface $layout
     * @return array
     */
    public function beforeSetLayout(View $subject, LayoutInterface $layout)
    {
        $order = $this->getOrder();
        $this->handleConfirmButton($subject, $order);
        $this->handleCancelButton($subject, $order);

        return [$layout];
    }

    /**
     * Retrieve order model object
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->registry->registry('sales_order');
    }

    /**
     * @param View $subject
     * @param Order $order
     */
    protected function handleConfirmButton(View $subject, Order $order)
    {
        if ($this->checkConfirmable($order)) {
            $subject->removeButton('order_creditmemo');
            $subject->removeButton('order_invoice');
            $subject->removeButton('order_ship');
            $onclickJs = 'jQuery(\'#confirm_order\').om(\'showConfirmOrderDialog\', \''
                . __('Please confirm sale agent and order source for this order?')
                . '\', \''
                . $subject->getUrl('customsales/order/confirm')
                . '\', \''
                . $this->serializer->serialize($this->dataHelper->getSaleAgentData())
                . '\', \''
                . $this->serializer->serialize($this->dataHelper->getOrderSourceData())
                . '\');';

            $subject->addButton(
                'confirm_order',
                [
                    'label' => __('Confirm Order'),
                    'class' => 'cancel primary action-secondary',
                    'id' => 'confirm_order',
                    'onclick' => $onclickJs,
                    'data_attribute' => [
                        'mage-init' => '{"Magenest_SalesOperations/js/om": {}}'
                    ]
                ]
            );
        }
    }

    /**
     * @param Order $order
     * @return bool
     */
    private function checkConfirmable(Order $order)
    {
        return $order->getStatus() == 'pending';
    }

    /**
     * @param View $subject
     * @param Order $order
     */
    protected function handleCancelButton(View $subject, Order $order)
    {
        if ($this->checkCancelable($order)) {
            $subject->removeButton('order_cancel');
            $onclickJs = 'jQuery(\'#order_cancel_with_reason\').om(\'showConfirmCancelDialog\', \''
                . __('Please confirm reason to cancel this order?')
                . '\', \''
                . $subject->getUrl('customsales/order/cancel')
                . '\', \''
                . $this->serializer->serialize($this->dataHelper->getCancelReasonData())
                . '\');';

            $subject->addButton(
                'order_cancel_with_reason',
                [
                    'label' => __('Cancel Order'),
                    'class' => 'cancel primary action-secondary',
                    'id' => 'order_cancel_with_reason',
                    'onclick' => $onclickJs,
                    'data_attribute' => [
                        'mage-init' => '{"Magenest_SalesOperations/js/om": {}}'
                    ]
                ]
            );
        }
    }

    /**
     * @param Order $order
     * @return bool
     */
    private function checkCancelable(Order $order)
    {
        return $order->canCancel()
            || ($order->hasInvoices() && !$order->hasShipments())
            || ($order->hasShipments() && $order->canCreditmemo());
    }
}
