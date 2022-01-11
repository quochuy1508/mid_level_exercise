<?php

namespace Magenest\CustomAdmin\Block\Adminhtml\Events\Edit;

use Magenest\CustomAdmin\Block\Adminhtml\Events\Edit\Tab\Info;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Tabs as WidgetTabs;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Json\EncoderInterface;
use \Magenest\CustomAdmin\Model\ResourceModel\Event\CollectionFactory;
use \Magenest\CustomAdmin\Model\ResourceModel\Event\Collection;

class Tabs extends WidgetTabs
{
    /**
     * @var CollectionFactory
     */
    private $eventCollection;

    public function __construct(
        Context $context,
        EncoderInterface $jsonEncoder,
        Session $authSession,
        CollectionFactory $eventCollection,
        array $data = []
    ) {
        $this->eventCollection = $eventCollection;
        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }

    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('events_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Events Information'));
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeToHtml()
    {
        /**
         * @var $eventCollection Collection
         */
        $eventCollection = $this->eventCollection->create();
        $eventCollection->setOrder('sort_order', 'ASC');

        foreach ($eventCollection->getItems() as $event) {
            $this->addTab(
                $event->getId(),
                [
                    'label' => $event->getEventName(),
                    'title' => $event->getEventName(),
                    'content' => $this->getLayout()->createBlock(
                        Info::class
                    )->setData('event', $event)->toHtml(),
                    'active' => true
                ]
            );
        }

        $this->addTab(
            'add_new_events',
            [
                'label' => __('Add New Event'),
                'title' => __('Add New Event'),
                'content' => $this->getLayout()->createBlock(
                    Info::class
                )->toHtml(),
                'active' => true
            ]
        );

        return parent::_beforeToHtml();
    }
}
