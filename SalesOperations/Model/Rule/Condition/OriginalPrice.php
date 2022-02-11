<?php

namespace Magenest\SalesOperations\Model\Rule\Condition;

use Magento\Checkout\Model\Session;

/**
 * Class Customer
 */
class OriginalPrice extends \Magento\Rule\Model\Condition\AbstractCondition
{
    /**
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    protected $sourceYesNo;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderFactory;

    /**
     * @var Session
     */
    protected $_session;

    /**
     * Constructor
     * @param \Magento\Rule\Model\Condition\Context $context
     * @param \Magento\Config\Model\Config\Source\Yesno $sourceYesNo
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Rule\Model\Condition\Context $context,
        \Magento\Config\Model\Config\Source\Yesno $sourceYesNo,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderFactory,
        Session $session,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->sourceYesNo = $sourceYesNo;
        $this->orderFactory = $orderFactory;
        $this->_session = $session;
    }

    /**
     * Load attribute options
     * @return $this
     */
    public function loadAttributeOptions()
    {
        $this->setAttributeOption([
            'original_price' => __('Original Price')
        ]);
        return $this;
    }

    /**
     * Get input type
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
     * Validate Customer First Order Rule Condition
     * @param \Magento\Framework\Model\AbstractModel $model
     * @return bool
     */
    public function validate(\Magento\Framework\Model\AbstractModel $model)
    {
        $items = $this->_session->getQuote()->getAllItems();
        $isOriginalPrice = 0;
        foreach ($items as $item) {
            /** @var \Magento\Quote\Model\Quote\Item $item */
            $finalPrice = $item->getProduct()->getFinalPrice();
            $originalPrice = $item->getProduct()->getPrice();

            if ($finalPrice == $originalPrice) {
                $isOriginalPrice = 1;
            }
        }

        $model->setData('original_price', $isOriginalPrice);
        return parent::validate($model);
    }
}
