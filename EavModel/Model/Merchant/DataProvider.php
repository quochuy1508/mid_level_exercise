<?php

namespace Magenest\EavModel\Model\Merchant;

use Magenest\EavModel\Model\Merchant;
use Magenest\EavModel\Model\ResourceModel\Merchant\Collection;
use Magenest\EavModel\Model\ResourceModel\Merchant\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    /**
     * @var Collection
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
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $merchantCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $merchantCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $merchantCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
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
        $items = $this->collection->addAttributeToSelect('*')->getItems();
        /** @var Merchant $merchant */
        foreach ($items as $merchant) {
            $this->loadedData[$merchant->getId()] = $merchant->getData();
        }

        $data = $this->dataPersistor->get('merchant');
        if (!empty($data)) {
            $merchant = $this->collection->getNewEmptyItem();
            $merchant->setData($data);
            $this->loadedData[$merchant->getId()] = $merchant->getData();
            $this->dataPersistor->clear('merchant');
        }

        return $this->loadedData;
    }
}
