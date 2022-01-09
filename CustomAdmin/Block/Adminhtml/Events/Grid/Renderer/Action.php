<?php

namespace Magenest\CustomAdmin\Block\Adminhtml\Events\Grid\Renderer;

use Magento\Framework\UrlInterface;

class Action extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * @var UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param UrlInterface $urlBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        UrlInterface $urlBuilder,
        array $data = []
    ) {
        $this->_urlBuilder = $urlBuilder;
        parent::__construct($context, $data);
    }

    /**
     * Render action
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $href = $this->_urlBuilder->getUrl('magenest/schedule/edit', ['schedule_id' => $row->getScheduleId()]);
        return '<a href="' . $href . '" target="_blank">' . __('Edit') . '</a>';
    }
}
