<?php

namespace Magenest\EavModel\Controller\Adminhtml\Merchant;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\ResultFactory;
use Magento\Indexer\Model\IndexerFactory;
use Psr\Log\LoggerInterface;

class BulkAssign extends Action
{
    const SIZE = 2000;

    /**
     * Customer collection factory
     *
     * @var CollectionFactory
     */
    protected $_customerCollectionFactory;

    /**
     * Customer collection factory
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Customer collection factory
     *
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var Attribute
     */
    protected $_eavAttribute;

    protected $indexFactory;

    protected $indexCollection;

    public function __construct(
        Context                                          $context,
        CollectionFactory                                $customerCollectionFactory,
        LoggerInterface                                  $logger,
        ResourceConnection                               $resourceConnection,
        Attribute                                        $eavAttribute,
        IndexerFactory                                   $indexFactory,
        \Magento\Indexer\Model\Indexer\CollectionFactory $indexCollection
    ) {
        $this->_customerCollectionFactory = $customerCollectionFactory;
        $this->logger = $logger;
        $this->resourceConnection = $resourceConnection;
        $this->_eavAttribute = $eavAttribute;
        $this->indexFactory = $indexFactory;
        $this->indexCollection = $indexCollection;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $customerIds = $this->_customerCollectionFactory->create()->getAllIds();
            if (is_array($customerIds)) {
                $merchantId = $this->getRequest()->getParam('merchant');
                $data = [];
                $attributeId = $this->_eavAttribute->getIdByCode('customer', 'merchant_id');
                //split list of IDs into arrays of 5000 IDs each
                foreach ($customerIds as $customerId) {
                    $data[] = [
                        'attribute_id' => $attributeId,
                        'entity_id' => (int)$customerId,
                        'value' => $merchantId
                    ];
                }
                $chunks = array_chunk($data, self::SIZE);
                $connection = $this->resourceConnection->getConnection();
                $table = $connection->getTableName('customer_entity_int');
                $connection->truncateTable($table);
                foreach ($chunks as $chunk) {
                    $connection->insertMultiple($table, $chunk);
                }

                $indexIdArray = $this->indexFactory->create()->load('customer_grid');
                $indexIdArray->reindexAll();

                $this->messageManager->addSuccessMessage(__('Assign merchant success.'));
            }

        } catch (Exception $exception) {
            $this->messageManager->addErrorMessage(__('Error when assign merchant.'));
            $this->logger->error($exception->getMessage());
        }
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $redirect->setPath('customer/index');
    }
}
