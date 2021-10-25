<?php

namespace Magenest\Cache\Model\CustomerDirectory;

use Magenest\Cache\Model\CustomerDirectory;
use Magenest\Cache\Model\ResourceModel\CustomerDirectory\CollectionFactory;
use Magento\Framework\App\Cache\StateInterface;
use Magento\Framework\App\Cache\Type\Config;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Ui\DataProvider\Modifier\PoolInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    /**
     * @var \Magenest\Cache\Model\ResourceModel\CustomerDirectory\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var CacheInterface
     */
    protected $_cache;

    /**
     * @var StateInterface
     */
    protected $_cacheState;

    /**
     * @var Json
     * @since 101.0.0
     */
    protected $serializer;

    /**
     * Cache flag
     *
     * @var bool|null
     */
    protected $_isCacheEnabled = null;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $blockCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param CacheInterface $cache
     * @param StateInterface $cacheState
     * @param Json $serializer
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $blockCollectionFactory,
        DataPersistorInterface $dataPersistor,
        CacheInterface $cache,
        StateInterface $cacheState,
        Json $serializer,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $blockCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->_cache = $cache;
        $this->_cacheState = $cacheState;
        $this->serializer = $serializer;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var \Magenest\Cache\Model\CustomerDirectory $block */
        foreach ($items as $block) {
            $this->loadedData[$block->getId()] = $block->getData();
        }

        $data = $this->dataPersistor->get('customer_directory');
        if (!empty($data)) {
            $block = $this->collection->getNewEmptyItem();
            $block->setData($data);
            $this->loadedData[$block->getId()] = $block->getData();
            $this->dataPersistor->clear('customer_directory');
        }
        return $this->loadedData;
    }
}
