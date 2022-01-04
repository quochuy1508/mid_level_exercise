<?php

namespace Magenest\CustomAdmin\Block\Adminhtml\Event\Form;

use Magenest\CustomAdmin\Block\Adminhtml\Event\Form\Tab\EventForm;
use Magenest\CustomAdmin\Model\ResourceModel\Event\Collection;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Json\EncoderInterface;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @var Collection
     */
    private $eventCollection;

    public function __construct(
        Context $context,
        EncoderInterface $jsonEncoder,
        Session $authSession,
        Collection $eventCollection,
        array $data = []
    ) {
        $this->eventCollection = $eventCollection;
        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }

    /**
     * Internal constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('magenest_event_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('All Events'));
    }

    /**
     * Preparing global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        foreach ($this->eventCollection->getItems() as $item) {
            $this->addTab(
                $item->getId(),
                [
                    'label' => $item->getEventName(),
                    'content' => $this->getLayout()->createBlock(
                        EventForm::class
                    )->setData('event', $item->getData())->toHtml(),
                    'active' => true
                ]
            );
        }
        return parent::_prepareLayout();
    }
}
