<?php

namespace Magenest\DatabaseConnection\Model;

use Magenest\DatabaseConnection\Api\CustomerTrainingRepositoryInterface;
use Magenest\DatabaseConnection\Api\Data;
use Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterfaceFactory;
use Magenest\DatabaseConnection\Model\ResourceModel\CustomerTraining as ResourceCustomerTraining;
use Magenest\DatabaseConnection\Model\ResourceModel\CustomerTraining\CollectionFactory as CustomerTrainingCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\EntityManager\HydratorInterface;

/**
 * Default CustomerTraining repo impl.
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomerTrainingRepository implements CustomerTrainingRepositoryInterface
{
    /**
     * @var ResourceCustomerTraining
     */
    protected $resource;

    /**
     * @var CustomerTrainingFactory
     */
    protected $customerTrainingFactory;

    /**
     * @var CustomerTrainingCollectionFactory
     */
    protected $customerTrainingCollectionFactory;

    /**
     * @var Data\CustomerTrainingSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var CustomerTrainingInterfaceFactory
     */
    protected $dataCustomerTrainingFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @param ResourceCustomerTraining $resource
     * @param CustomerTrainingFactory $customerTrainingFactory
     * @param CustomerTrainingInterfaceFactory $dataCustomerTrainingFactory
     * @param CustomerTrainingCollectionFactory $customerTrainingCollectionFactory
     * @param Data\CustomerTrainingSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface|null $collectionProcessor
     * @param HydratorInterface|null $hydrator
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        ResourceCustomerTraining $resource,
        CustomerTrainingFactory $customerTrainingFactory,
        CustomerTrainingInterfaceFactory $dataCustomerTrainingFactory,
        CustomerTrainingCollectionFactory $customerTrainingCollectionFactory,
        Data\CustomerTrainingSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor = null,
        ?HydratorInterface $hydrator = null
    ) {
        $this->resource = $resource;
        $this->customerTrainingFactory = $customerTrainingFactory;
        $this->customerTrainingCollectionFactory = $customerTrainingCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataCustomerTrainingFactory = $dataCustomerTrainingFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
        $this->hydrator = $hydrator ?? ObjectManager::getInstance()->get(HydratorInterface::class);
    }

    /**
     * Save CustomerTraining data
     *
     * @param \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface $customerTraining
     * @return \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface
     * @throws CouldNotSaveException
     */
    public function save(Data\CustomerTrainingInterface $customerTraining)
    {
        try {
            $this->resource->save($customerTraining);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $customerTraining;
    }

    /**
     * Load CustomerTraining data by given CustomerTraining Identity
     *
     * @param string $id
     * @return \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id)
    {
        $customerTraining = $this->customerTrainingFactory->create();
        $this->resource->load($customerTraining, $id);
        if (!$customerTraining->getId()) {
            throw new NoSuchEntityException(__('The customerTraining with the "%1" ID doesn\'t exist.', $id));
        }
        return $customerTraining;
    }

    /**
     * Load CustomerTraining data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Magenest\DatabaseConnection\Api\Data\CustomerTrainingSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var \Magenest\DatabaseConnection\Model\ResourceModel\CustomerTraining\Collection $collection */
        $collection = $this->customerTrainingCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var Data\CustomerTrainingSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete CustomerTraining
     *
     * @param \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface $customerTraining
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\CustomerTrainingInterface $customerTraining)
    {
        try {
            $this->resource->delete($customerTraining);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete CustomerTraining by given CustomerTraining Identity
     *
     * @param string $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }

    /**
     * Retrieve collection processor
     *
     * @return CollectionProcessorInterface
     */
    private function getCollectionProcessor()
    {
        //phpcs:disable Magento2.PHP.LiteralNamespaces
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Magenest\DatabaseConnection\Model\Api\SearchCriteria\CustomerTrainingCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }
}
