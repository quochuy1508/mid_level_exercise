<?php

namespace Magenest\EavModel\Ui\DataProvider\Merchant;

use Magenest\EavModel\Model\ResourceModel\Merchant\Collection;
use Magenest\EavModel\Model\ResourceModel\Merchant\CollectionFactory;

use Magento\Framework\App\ObjectManager;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;

class MerchantDataProvider extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    protected $merchantsCollectionFactory;

    /**
     * @var PoolInterface
     */
    private $modifiersPool;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $merchantsCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $merchantsCollectionFactory,
        array $meta = [],
        array $data = [],
        PoolInterface $modifiersPool = null
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->merchantsCollectionFactory = $merchantsCollectionFactory;
        $this->modifiersPool = $modifiersPool ?: ObjectManager::getInstance()->get(PoolInterface::class);
        $this->initCollection();
    }

    /**
     * @inheritDoc
     */
    public function getCollection()
    {
        /**
         * @var Collection $collection
         */
        $collection = parent::getCollection();
        $collection->addAttributeToSelect('*');
        return $collection;
    }


    /**
     * @inheritdoc
     */
    public function initCollection()
    {
        $collection = $this->merchantsCollectionFactory->create();
        $this->collection = $collection;
    }

    public function getSearchCriteria(){
        return $this->getCollection();
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }
        $items = $this->getCollection()->toArray();

        $data = [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => array_values($items),
        ];

        /** @var ModifierInterface $modifier */
        foreach ($this->modifiersPool->getModifiersInstances() as $modifier) {
            $data = $modifier->modifyData($data);
        }
        return $data;
    }

    /**
     * @inheritdoc
     * @since 103.0.0
     */
    public function getMeta()
    {
        $meta = parent::getMeta();

        /** @var ModifierInterface $modifier */
        foreach ($this->modifiersPool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }
}
