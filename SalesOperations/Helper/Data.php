<?php

namespace Magenest\SalesOperations\Helper;

use Magento\Backend\App\ConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\User\Model\ResourceModel\User\Collection;
use Magento\User\Model\ResourceModel\User\CollectionFactory;

class Data extends AbstractHelper
{
    const CUSTOMER_RANK_PATH = 'sales_operations/customer_rank/accumulation';
    const ORDER_SOURCE_PATH = 'sales_operations/order_source/source';
    const ORDER_CANCEL_REASON_PATH = 'sales_operations/order_cancel/reason';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ConfigInterface
     */
    protected $_config;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param ConfigInterface $config
     * @param SerializerInterface $serializer
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        ConfigInterface $config,
        SerializerInterface $serializer,
        CollectionFactory $collectionFactory
    ) {
        $this->_config = $config;
        $this->serializer = $serializer;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Retrieve customer ranks
     *
     * @return array
     */
    public function getCustomerRankData()
    {
        $result = [];
        $value = $this->_config->getValue(self::CUSTOMER_RANK_PATH);

        if ($value) {
            $options = $this->serializer->unserialize($value);
            foreach ($options as $key => $option) {
                $result[] = [
                    'label' => $option['rank_name'],
                    'value' => $key
                ];
            }
        }
        return $result;
    }

    /**
     * Retrieve customer ranks
     *
     * @return array
     */
    public function getCustomerRankDataRaw()
    {
        $value = $this->_config->getValue(self::CUSTOMER_RANK_PATH);
        $options = $this->serializer->unserialize($value);
        return $options;
    }

    /**
     * Retrieve order source
     *
     * @return array
     */
    public function getOrderSourceData()
    {
        $result = [];
        $value = $this->_config->getValue(self::ORDER_SOURCE_PATH);

        if ($value) {
            $options = $this->serializer->unserialize($value);
            foreach ($options as $key => $option) {
                $result[] = [
                    'label' => $option['order_source'],
                    'value' => $option['order_source']
                ];
            }
        }

        return $result;
    }

    /**
     * Retrieve order source
     *
     * @return array
     */
    public function getCancelReasonData()
    {
        $result = [];

        $result[] = ['label' => __("Other Reason"), 'value' => __("Other Reason")];
        $value = $this->_config->getValue(self::ORDER_CANCEL_REASON_PATH);
        if ($value) {
            $options = $this->serializer->unserialize($value);
            foreach ($options as $key => $option) {
                $result[] = [
                    'label' => $option['reason'],
                    'value' => $option['reason']
                ];
            }
        }
        return $result;
    }

    /**
     * Retrieve sale agent
     *
     * @return array
     */
    public function getSaleAgentData()
    {
        $result = [];
        foreach ($this->collectionFactory->create()->getItems() as $item) {
            $result[] = [
                'label' => $item->getName(),
                'value' => $item->getId(),
            ];
        }
        return $result;
    }
}
