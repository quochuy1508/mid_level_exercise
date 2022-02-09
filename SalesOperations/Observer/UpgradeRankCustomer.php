<?php

namespace Magenest\SalesOperations\Observer;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Reports\Model\ResourceModel\Order\Collection;

class UpgradeRankCustomer implements ObserverInterface
{
    /** @var ResourceConnection  */
    private $resourceConnection;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        ResourceConnection $resourceConnection,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if ($order && $order->getCustomerId()) {
            $data = $this->addSumTotalsByCustomer(1);
        }
    }

    /**
     * Add summary average totals
     *
     * @param int $storeId
     * @return \Magento\Framework\DB\Select
     */
    public function addSumTotalsByCustomer($customerId, $storeId = 0)
    {
        $connection = $this->resourceConnection->getConnection();
        /**
         * calculate average and total amount
         */
        $expr = $this->getTotalsExpressionWithDiscountRefunded(
            $storeId,
            $connection->getIfNullSql('main_table.base_subtotal_refunded', 0),
            $connection->getIfNullSql('main_table.base_subtotal_canceled', 0),
            $connection->getIfNullSql('ABS(main_table.base_discount_refunded)', 0),
            $connection->getIfNullSql('ABS(main_table.base_discount_canceled)', 0)
        );
        $salesOrderTable = $connection->getTableName('sales_order');
        $connection->select()->from($salesOrderTable)->where('customer_id', ['eq' => $customerId])
        $collection = $this->collectionFactory->create()->addFieldToSelect([])->addFieldToFilter('customer_id', ['eq' => $customerId])->getSelect()->columns(
            ['orders_sum_amount' => "SUM({$expr})"]
        );

        return $collection;
    }

    /**
     * Get SQL expression for totals with discount refunded.
     *
     * @param int $storeId
     * @param string $baseSubtotalRefunded
     * @param string $baseSubtotalCanceled
     * @param string $baseDiscountRefunded
     * @param string $baseDiscountCanceled
     * @return string
     */
    private function getTotalsExpressionWithDiscountRefunded(
        $storeId,
        $baseSubtotalRefunded,
        $baseSubtotalCanceled,
        $baseDiscountRefunded,
        $baseDiscountCanceled
    ) {
        $template = ($storeId != 0)
            ? '(main_table.base_subtotal - %2$s - %1$s - (ABS(main_table.base_discount_amount) - %3$s - %4$s))'
            : '((main_table.base_subtotal - %1$s - %2$s - (ABS(main_table.base_discount_amount) - %3$s - %4$s)) '
            . ' * main_table.base_to_global_rate)';
        return sprintf(
            $template,
            $baseSubtotalRefunded,
            $baseSubtotalCanceled,
            $baseDiscountRefunded,
            $baseDiscountCanceled
        );
    }
}
