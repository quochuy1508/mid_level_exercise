<?php

namespace Magenest\SalesOperations\Controller\Adminhtml\Order;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\Translate\InlineInterface;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Controller\Adminhtml\Order;
use Magento\Sales\Model\Order\Invoice;
use Psr\Log\LoggerInterface;

class Cancel extends Order
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Sales::cancel';

    /** @var InvoiceRepositoryInterface */
    private $invoiceRepository;

    public function __construct(
        Action\Context           $context,
        Registry                 $coreRegistry,
        FileFactory              $fileFactory,
        InlineInterface          $translateInline,
        PageFactory              $resultPageFactory,
        JsonFactory              $resultJsonFactory,
        LayoutFactory            $resultLayoutFactory,
        RawFactory               $resultRawFactory,
        OrderManagementInterface $orderManagement,
        OrderRepositoryInterface $orderRepository,
        LoggerInterface          $logger,
        InvoiceRepositoryInterface          $invoiceRepository
    ) {
        $this->invoiceRepository = $invoiceRepository;
        parent::__construct(
            $context,
            $coreRegistry,
            $fileFactory,
            $translateInline,
            $resultPageFactory,
            $resultJsonFactory,
            $resultLayoutFactory,
            $resultRawFactory,
            $orderManagement,
            $orderRepository,
            $logger
        );
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $order = $this->_initOrder();
        if ($order) {
            try {
                if ($order->canCancel()) {
                    $this->orderManagement->cancel($order->getEntityId());
                } elseif ($order->hasInvoices() && !$order->hasShipments()) {
                    $originOrder = $this->orderRepository->get($order->getId());
                    if ($originOrder->getInvoiceCollection()->getSize()) {
                        /** @var Invoice $invoice */
                        foreach ($originOrder->getInvoiceCollection() as $invoice) {
                            $invoice->setState(Invoice::STATE_OPEN); // Force cancel invoice
                            if ($invoice->canCancel()) {
                                $invoice->cancel();
                                $this->invoiceRepository->save($invoice);
                            }
                        }
                    }

                    $originOrder->cancel();
                    $this->orderRepository->save($originOrder);
                }

                $cancelReason = $this->_request->getParam('cancel_reason');
                if ($cancelReason) {
                    $otherReason = $this->_request->getParam('other_reason');
                    $isCancel = $this->_request->getParam('cancel_notification');
                    $order->addCommentToStatusHistory('Cancel Order with reason: ' . $cancelReason . ' - ' . $otherReason, $order->getStatus(), $isCancel)->save();
                }

                $this->messageManager->addSuccessMessage(__('You canceled the order.'));

            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(__('You have not canceled the item.'));
                $this->_objectManager->get(LoggerInterface::class)->critical($e);
            }
            return $resultRedirect->setPath('sales/order/view', ['order_id' => $order->getId()]);
        }
        return $resultRedirect->setPath('sales/*/');
    }
}
