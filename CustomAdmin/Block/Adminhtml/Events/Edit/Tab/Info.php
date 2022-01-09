<?php

namespace Magenest\CustomAdmin\Block\Adminhtml\Events\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Cms\Model\Wysiwyg\Config;

class Info extends Generic implements TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Config $wysiwygConfig
//     * @param Status $newsStatus
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form fields
     *
     * @return \Magento\Backend\Block\Widget\Form
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var $model \Magenest\CustomAdmin\Model\Event */
        $model = $this->getData('event');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        if ($model && $model->getId()) {
            $form->setHtmlIdPrefix('events_' . $model->getId() . '_');
            $form->setFieldNameSuffix('events_' . $model->getId());
        } else {
            $form->setHtmlIdPrefix('news_');
            $form->setFieldNameSuffix('news');
        }


        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Event Information')]
        );

        if ($model && $model->getId()) {
            $fieldset->addField(
                'event_id',
                'hidden',
                ['name' => 'event_id']
            );
        }
        $fieldset->addField(
            'event_name',
            'text',
            [
                'name'        => 'event_name',
                'label'    => __('Event Name'),
                'required'     => true
            ]
        );

        $fieldset->addField(
            'days_before_event',
            'text',
            [
                'name'        => 'days_before_event',
                'label'    => __('Day Before Event'),
                'required'     => true
            ]
        );

        $dateFormat = $this->_localeDate->getDateFormatWithLongYear();
        $fieldset->addField(
            'event_date',
            'date',
            [
                'label' => __('Event Date'),
                'title' => __('Event Date'),
                'name' => 'event_date',
                'date_format' => $dateFormat,
                'required' => true,
                'class' => 'validate-date',
            ]
        );

        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name'        => 'sort_order',
                'label'    => __('Sort Order'),
                'required'     => true,
                'class' => 'validate-digits validate-not-negative-number',
            ]
        );

        if ($model && $model->getData()) {
            $fieldsetSchedule = $form->addFieldset(
                'schedule_fieldset',
                ['legend' => __('Default Schedule')]
            );

            $fieldsetSchedule->addField(
                'default_schedule',
                'text',
                ['name' => 'default_schedule', 'class' => 'required-entry']
            );

            $form->getElement(
                'default_schedule'
            )->setRenderer(
                $this->getLayout()->createBlock(Grid::class)->setData('event_id', $model->getId())
            );
        }


        if ($model && $model->getData()) {
            $data = $model->getData();
            $form->setValues($data);
        }

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('News Info');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('News Info');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
