<?php

namespace Magenest\CustomAdmin\Block\Adminhtml\Events\Grid\Column\Renderer;

use Magento\Backend\Block\Context;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class EventTime extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    protected $date;

    public function __construct(
        Context $context,
        DateTime $date,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->date = $date;
    }

    /**
     * Renders grid column
     *
     * @param DataObject $row
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function render(DataObject $row)
    {
        $data = $row->getData($this->getColumn()->getIndex());
        return $this->date->date('h:i a', $data);
    }
}
