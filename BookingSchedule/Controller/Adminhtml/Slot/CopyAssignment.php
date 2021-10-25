<?php

namespace Magenest\BookingSchedule\Controller\Adminhtml\Slot;

use Magenest\BookingSchedule\Api\DuplicateBookingScheduleInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\LocalizedException;

class CopyAssignment extends Action implements HttpPostActionInterface
{
    /*
     * DuplicateBookingScheduleInterface
     */
    private $duplicateBookingSchedule;

    /**
     * @param DuplicateBookingScheduleInterface $duplicateBookingSchedule
     * @param Context $context
     */
    public function __construct(
        DuplicateBookingScheduleInterface $duplicateBookingSchedule,
        Context $context
    ) {
        $this->duplicateBookingSchedule = $duplicateBookingSchedule;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $numberToCopy = $this->getRequest()->getParam('number');
        if ($numberToCopy) {
            try {
                $result = $this->duplicateBookingSchedule->execute($numberToCopy);
                $this->messageManager->addSuccessMessage(__('You duplicated the product.'));
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the block.'));
            }
        }
        return $resultRedirect->setPath('*/view/index');
    }
}
