<?php

namespace Magenest\CustomAdmin\Block\Adminhtml\Customer;

use Magento\Backend\Block\Template;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magenest\CustomAdmin\Model\ResourceModel\Event\Collection;
use Magenest\CustomAdmin\Model\ResourceModel\Event\CollectionFactory;
use Magento\Framework\Serialize\Serializer\Serialize;

class Modal extends Template
{
    /**
     * @var CollectionFactory
     */
    private $eventCollection;

    /**
     * @var Serialize
     */
    private $serialize;

    /**
     * @param Template\Context $context
     * @param CollectionFactory $eventCollection
     * @param Serialize $serialize
     * @param array $data
     * @param JsonHelper|null $jsonHelper
     * @param DirectoryHelper|null $directoryHelper
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $eventCollection,
        Serialize $serialize,
        array            $data = [],
        ?JsonHelper      $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    ) {
        $this->eventCollection = $eventCollection;
        $this->serialize = $serialize;
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
    }

    public function getAllEventData()
    {
        $result = [];
        /**
         * @var Collection $eventCollection
         */
        $eventCollection = $this->eventCollection->create();
        $connection = $eventCollection->getConnection();
        $tableName = $connection->getTableName('magenest_schedule');
        $eventCollection->addFieldToSelect('event_name');
        $eventCollection->getSelect()->join(
            ['schedule' => $tableName],
            'main_table.event_id = schedule.event_id',
            ['event_time', 'schedule_id', 'id' => 'schedule.event_id']
        );

        if (count($items = $eventCollection->getItems())) {
            foreach ($items as $item) {
                $result[$item['id']]['name'] = $item['event_name'];
                $result[$item['id']]['child'][] = $item->getData();
            }
        }
        return $result;
    }

    public function convertToJsonValue($data)
    {
        return $this->serialize->serialize($data);
    }
}
