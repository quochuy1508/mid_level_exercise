<?php

namespace Magenest\CustomAdmin\Block\Adminhtml\Events\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magenest\CustomAdmin\Model\ResourceModel\Schedule\CollectionFactory;
use Magenest\CustomAdmin\Model\ResourceModel\Schedule\Collection;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Framework\Registry;
use Magenest\CustomAdmin\Model\Schedule;

class Grid extends Extended implements RendererInterface
{
    /**
     * @var Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var Schedule
     */
    protected $schedule;

    public function __construct(
        Context $context,
        Data $backendHelper,
        CollectionFactory $collectionFactory,
        Schedule $schedule,
        Registry $coreRegistry,
        array $data = []
    )
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_collectionFactory = $collectionFactory;
        $this->schedule = $schedule;
        parent::__construct($context, $backendHelper, $data);
    }

    public function getHeadersVisibility()
    {
        return $this->getCollection()->getSize() >= 0;
    }

    /**
     * @inheritDoc
     */
    public function render(AbstractElement $element)
    {
        return $this->toHtml();
    }

    protected
    function _construct()
    {
        parent::_construct();
        $this->setId('view_schedule_grid');
        $this->setDefaultSort('date_schedule');
        $this->setDefaultDir('desc');
//        $this->setSortable(false);
//        $this->setUseAjax(true);
        $this->setPagerVisibility(false);
        $this->setFilterVisibility(false);
    }

    protected function _prepareCollection()
    {
        /**
         * @var Collection $collection
         */
        $collection = $this->_collectionFactory->create();
        if ($eventId = $this->getData('event_id')) {
            $collection->addFieldToFilter('event_id', $eventId)
            ->addFieldToSelect(['schedule_id', 'day_schedule', 'date_schedule', 'details_message', 'event_time']);
            $this->setCollection($collection);
        }

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'schedule_id',
            [
                'header' => __('ID'),
                'index' => 'schedule_id',
                'type' => 'text',
                'width' => '100px'
            ]
        );

        $this->addColumn(
            'day_schedule',
            [
                'header' => __('Day'),
                'index' => 'day_schedule',
                'type' => 'options',
                'options' => $this->schedule->getAvailableScheduleDay(),
                'width' => '100px'
            ]
        );
        $this->addColumn(
            'date_schedule',
            ['header' => __('Date'), 'index' => 'date_schedule', 'type' => 'date', 'width' => '100px']
        );
        $this->addColumn(
            'details_message',
            ['header' => __('Details Message'), 'index' => 'details_message', 'type' => 'text', 'width' => '100px']
        );
        $this->addColumn(
            'event_time',
            [
                'header' => __('Event Time'),
                'index' => 'event_time',
                'renderer' => \Magenest\CustomAdmin\Block\Adminhtml\Events\Grid\Column\Renderer\EventTime::class,
            ]
        );

        $this->addColumn(
            'schedule_action',
            [
                'header' => __('Action'),
                'sortable' => false,
                'filter' => false,
                'renderer' => \Magenest\CustomAdmin\Block\Adminhtml\Events\Grid\Renderer\Action::class,
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );
        return parent::_prepareColumns();
    }
}
