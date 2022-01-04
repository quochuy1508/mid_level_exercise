<?php

namespace Magenest\CustomAdmin\Block\Adminhtml\Event\Form;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;

/**
 * @api
 * @since 100.0.2
 */
class Edit extends Container
{
    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * Edit constructor.
     *
     * @param Registry $coreRegistry
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Registry $coreRegistry,
        Context $context,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;

        parent::__construct($context, $data);
    }

    /**
     * Initialize Account edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'event_id';
        $this->_blockGroup = 'Magenest_CustomAdmin';
        $this->_controller = 'adminhtml_event_form';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save'));
    }
}
