<?php

namespace Magenest\SalesOperations\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    const CUSTOMER_RANK_PATH = 'promo/customer_rank/accumulation';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var \Magento\Backend\App\ConfigInterface
     */
    protected $_config;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Backend\App\ConfigInterface $config
     * @param SerializerInterface $serializer
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Backend\App\ConfigInterface $config,
        SerializerInterface $serializer
    ) {
        $this->_config = $config;
        $this->serializer = $serializer;
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
        $options = $this->serializer->unserialize($value);
        foreach ($options as $key => $option) {
            $result[] = [
                'label' => $option['rank_name'],
                'value' => $key
            ];
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
}
