<?php

namespace Magenest\CustomAdmin\Controller\Adminhtml\Event;

use _PHPStan_76800bfb5\Nette\Neon\Exception;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use \Magenest\CustomAdmin\Model\EventFactory;
use \Magenest\CustomAdmin\Model\Event;
use \Magenest\CustomAdmin\Model\ResourceModel\Event as EventResourceModel;
use \Magenest\CustomAdmin\Model\ScheduleFactory;
use \Magenest\CustomAdmin\Model\Schedule;
use \Magenest\CustomAdmin\Model\ResourceModel\Schedule as ScheduleResourceModel;
use Magento\Framework\Registry;

class Save extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var EventFactory
     */
    protected $eventFactory;

    /**
     * @var ScheduleFactory
     */
    protected $scheduleFactory;

    /**
     * @var EventResourceModel
     */
    protected $eventResourceModel;

    /**
     * @var ScheduleResourceModel
     */
    protected $scheduleResourceModel;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     * @param EventFactory $eventFactory
     * @param ScheduleFactory $scheduleFactory
     * @param EventResourceModel $eventResourceModel
     * @param ScheduleResourceModel $scheduleResourceModel
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        DataPersistorInterface $dataPersistor,
        EventFactory $eventFactory,
        ScheduleFactory $scheduleFactory,
        EventResourceModel $eventResourceModel,
        ScheduleResourceModel $scheduleResourceModel
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->eventFactory = $eventFactory;
        $this->scheduleFactory = $scheduleFactory;
        $this->eventResourceModel = $eventResourceModel;
        $this->scheduleResourceModel = $scheduleResourceModel;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws Exception
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $dataPost = $this->getRequest()->getPostValue();
        if ($dataPost) {
            foreach ($dataPost as $key => $event) {
                if (strpos($key, 'events') !== false) {
                    $eventModel = $this->eventFactory->create();
                    if (isset($event['event_id'])) {
                        $this->eventResourceModel->load($eventModel, $event['event_id']);
                    }
                    $eventModel->setData($event);
                    try {
                        $this->eventResourceModel->save($eventModel);
                        $eventDate = $eventModel->getEventDate();
                        $daysBeforeEvent = $eventModel->getDaysBeforeEvent();
                        $dataSchedule = [];
                        foreach (range(0, $daysBeforeEvent) as $i) {
                            $dateTimeSchedule = date('Y-m-d', strtotime("-{$i} day", strtotime($eventDate)));
                            $dataSchedule[] = [
                                'day_schedule' => date('w', strtotime($dateTimeSchedule)),
                                'date_schedule' => $dateTimeSchedule,
                                'details_message' => 'Test',
                                'event_time' => date('Y-m-d H:i:s', strtotime($dateTimeSchedule)),
                                'event_id' => $eventModel->getId()
                            ];
                        }
                        $tableSchedule = $this->scheduleResourceModel->getMainTable();
                        $this->scheduleResourceModel->getConnection()->delete($tableSchedule, ['event_id=?' => $eventModel->getId()]);
                        $this->scheduleResourceModel->getConnection()->insertMultiple($tableSchedule, $dataSchedule);
                    } catch (\Exception $exception) {
                        throw new Exception($exception->getMessage());
                    }
                }
            }
            $this->dataPersistor->set('cms_block', $dataPost);
        }
        return $resultRedirect->setPath('*/*/form');
    }
}
