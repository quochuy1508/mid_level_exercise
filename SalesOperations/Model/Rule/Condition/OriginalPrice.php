<?php

namespace Magenest\SalesOperations\Model\Rule\Condition;

use Magento\Checkout\Model\Session;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Framework\Model\AbstractModel;
use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\Rule\Model\Condition\Context;

class OriginalPrice extends AbstractCondition
{
    /**
     * @var Session
     */
    protected $_checkoutSession;

    /**
     * @var Yesno
     */
    protected $sourceYesNo;

    /**
     * @param Context $context
     * @param Session $checkoutSession
     * @param Yesno $sourceYesNo
     * @param array $data
     */
    public function __construct(
        Context $context,
        Session $checkoutSession,
        Yesno $sourceYesNo,
        array   $data = []
    ) {
        parent::__construct($context, $data);
        $this->_checkoutSession = $checkoutSession;
        $this->sourceYesNo = $sourceYesNo;
    }

    /**
     * @return $this|OriginalPrice
     */
    public function loadAttributeOptions()
    {
        $this->setAttributeOption([
            'original_price' => __('Original Price')
        ]);
        return $this;
    }

    /**
     * @return string
     */
    public function getInputType()
    {
        return 'select';
    }

    /**
     * Get value element type
     * @return string
     */
    public function getValueElementType()
    {
        return 'select';
    }

    /**
     * Get value select options
     * @return array|mixed
     */
    public function getValueSelectOptions()
    {
        if (!$this->hasData('value_select_options')) {
            $this->setData(
                'value_select_options',
                $this->sourceYesNo->toOptionArray()
            );
        }
        return $this->getData('value_select_options');
    }


    /**
     * @param AbstractModel $model
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function validate(AbstractModel $model)
    {
        $model->setData('original_price', true);  // validation value
        return parent::validate($model);
    }
}
