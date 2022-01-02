<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\DatabaseConnection\Model\CustomerTraining;

use Magenest\DatabaseConnection\Model\ResourceModel\CustomerTraining\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

/**
 * Cms Page DataProvider
 */
class DataProvider extends ModifierPoolDataProvider
{
    /**
     * @var \Magenest\DatabaseConnection\Model\ResourceModel\CustomerTraining\Collection
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
     * @param CollectionFactory $customerTrainingCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $customerTrainingCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $customerTrainingCollectionFactory->create();
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
        $items = $this->collection->getItems();
        foreach ($items as $customerTraining) {
            $this->loadedData[$customerTraining->getId()] = $customerTraining->getData();
        }

        $data = $this->dataPersistor->get('customer_training');
        if (!empty($data)) {
            $customerTraining = $this->collection->getNewEmptyItem();
            $customerTraining->setData($data);
            $this->loadedData[$customerTraining->getId()] = $customerTraining->getData();
            $this->dataPersistor->clear('customer_training');
        }

        return $this->loadedData;
    }
}
