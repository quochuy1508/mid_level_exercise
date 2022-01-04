<?php

namespace Magenest\CustomAdmin\Block\Adminhtml\Event\Form\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Phrase;
use Magento\Framework\Data\FormFactory;

class EventForm extends Form implements TabInterface
{
    /**
     * @var FormFactory
     */
    private $_formFactory;

    public function __construct(
        Context $context,
        FormFactory $formFactory,
        array $data = [],
        Form\Element\ElementCreator $creator = null
    ) {
        $this->_formFactory = $formFactory;
        parent::__construct($context, $data, $creator);
    }

    /**
     * Prepare label for tab
     *
     * @return Phrase
     */
    public function getTabLabel()
    {
        return __('Add New Event');
    }

    /**
     * Prepare title for tab
     *
     * @return Phrase
     */
    public function getTabTitle()
    {
        return __('Add New Event');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setActive(true);
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $eventData = $this->getData('event') ?? null;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Event Details')]);

        $this->_addElementTypes($fieldset);

        $fieldset->addField(
            'event_name',
            'text',
            [
                'name' => 'event_name',
                'label' => __('Event Name'),
                'title' => __('Event Name'),
                'required' => true,
                'value' => $eventData ? $eventData['event_name'] : null
            ]
        );

        $fieldset->addField(
            'days_before_event',
            'text',
            [
                'name' => 'days_before_event',
                'label' => __('Days Before Event'),
                'title' => __('Days Before Event'),
                'required' => true,
                'value' => $eventData ? $eventData['days_before_event'] : null
            ]
        );

        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::MEDIUM);

        $fieldset->addField(
            'event_date',
            'date',
            [
                'name' => 'event_date',
                'label' => __('Event Date'),
                'title' => __('Event Date'),
                'required' => true,
                'date_format' => $dateFormat,
                'value' => $eventData ? $eventData['event_date'] : null
            ]
        );

        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name' => 'sort_order',
                'label' => __('Sort Order'),
                'title' => __('Sort Order'),
                'required' => true,
                'value' => $eventData ? $eventData['sort_order'] : null
            ]
        );

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @return array|mixed|null
     */
    public function getEventData()
    {
        return $this->getData('event') ?? null;
    }
}
