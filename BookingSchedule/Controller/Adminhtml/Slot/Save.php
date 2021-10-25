<?php

namespace Magenest\BookingSchedule\Controller\Adminhtml\Slot;

use Magenest\BookingSchedule\Model\BookingScheduleSlot;
use Magenest\BookingSchedule\Model\BookingScheduleSlotFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magenest\BookingSchedule\Model\ResourceModel\BookingScheduleSlot as SlotResourceModel;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action implements HttpPostActionInterface
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var SlotResourceModel
     */
    private $slotResourceModel;

    /**
     * @var BookingScheduleSlotFactory
     */
    private $bookingScheduleSlotFactory;

    /**
     * @param Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Psr\Log\LoggerInterface $logger
     * @param SlotResourceModel $slotResourceModel
     * @param BookingScheduleSlotFactory $bookingScheduleSlotFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Psr\Log\LoggerInterface $logger,
        SlotResourceModel $slotResourceModel,
        BookingScheduleSlotFactory                 $bookingScheduleSlotFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->logger = $logger;
        $this->slotResourceModel = $slotResourceModel;
        $this->bookingScheduleSlotFactory = $bookingScheduleSlotFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $postDatas = $this->getRequest()->getPostValue();
            foreach ($postDatas['data'] as $data) {
                /** @var BookingScheduleSlot $slotModel */
                $slotModel = $this->bookingScheduleSlotFactory->create();
                if ($id = $data['entity_id']) {
                    try {
                        $this->slotResourceModel->load($slotModel, $id);
                        $slotModel->setData($data);
                        $this->slotResourceModel->save($slotModel);
                    } catch (LocalizedException $e) {
                        $this->messageManager->addErrorMessage(__('This block no longer exists.'));
                        return $this->jsonResponse(['success' => false, 'message' => __('This block no longer exists.')]);
                    }
                }
            }
            return $this->jsonResponse(['success' => true]);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            return $this->jsonResponse(['success' => false]);
        }
    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }
}
