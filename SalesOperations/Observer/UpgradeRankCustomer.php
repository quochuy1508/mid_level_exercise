<?php

namespace Magenest\SalesOperations\Observer;

use Magenest\SalesOperations\Helper\Data;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Reports\Model\ResourceModel\Order\Collection;
use Psr\Log\LoggerInterface;

class UpgradeRankCustomer implements ObserverInterface
{
    /** @var ResourceConnection  */
    private $resourceConnection;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Data
     */
    private $dataHelper;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $_customerRepositoryInterface;

    public function __construct(
        ResourceConnection $resourceConnection,
        LoggerInterface $logger,
        Data $dataHelper,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
        $this->dataHelper = $dataHelper;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if ($order && $order->getCustomerId()) {
            $data = $this->addSumTotalsByCustomer($order->getCustomerId());
            if ($data) {
                $dataRanks = $this->dataHelper->getCustomerRankDataRaw();
                arsort($dataRanks, SORT_NUMERIC);

                foreach ($dataRanks as $key => $dataRank) {
                    if ($dataRank['accumulation'] < $data) {
                        $customer = $this->_customerRepositoryInterface->getById($order->getCustomerId());
                        $customer->setCustomAttribute('customer_rank', $key);
                        $this->_customerRepositoryInterface->save($customer);
                        break;
                    }
                }
            }
        }
    }

    /**
     * Add summary average totals
     *
     * @param $customerId
     * @param int $storeId
     * @return string|null
     */
    public function addSumTotalsByCustomer($customerId, $storeId = 0)
    {
        try {
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
            $select = $connection->select()->from(['main_table' => $salesOrderTable], [])->columns(
                ['orders_sum_amount' => "SUM({$expr})"]
            )->where('main_table.customer_id = ?', $customerId);

            return $connection->fetchOne($select);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return null;
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
