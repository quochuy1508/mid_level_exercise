<?php

namespace Magenest\SalesOperations\Controller\Adminhtml\Order;

use Magento\Sales\Controller\Adminhtml\Order;

class Confirm extends Order
{
    /**
     * @inheritDoc
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $order = $this->_initOrder();

        if ($order) {
            try {
                $order->setStatus('confirmed')
                    ->setSaleAgentId($this->getRequest()->getParam('sale_agent') ?? null)
                    ->setOrderSource($this->getRequest()->getParam('order_source') ?? null)
                    ->save();
                $this->messageManager->addSuccessMessage(__('You confirmed the order.'));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('You have not confirmed the item.'));
                $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
            }
            return $resultRedirect->setPath('sales/order/view', ['order_id' => $order->getId()]);
        }
        return $resultRedirect->setPath('sales/*/');
    }
}
