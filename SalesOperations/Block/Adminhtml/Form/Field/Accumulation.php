<?php
namespace Magenest\SalesOperations\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Ranges
 */
class Accumulation extends AbstractFieldArray
{
    /**
     * Prepare rendering the new field by adding all the needed columns
     */
    protected function _prepareToRender()
    {
        $this->addColumn('rank_name', ['label' => __('Rank Name'), 'class' => 'required-entry']);
        $this->addColumn('accumulation', ['label' => __('Accumulation'), 'class' => 'required-entry validate-greater-than-zero validate-number']);
        $this->addColumn('discount', ['label' => __('Discount (% grand total or fixed amount)'), 'class' => 'required-entry']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];
        $row->setData('option_extra_attrs', $options);
    }
}
