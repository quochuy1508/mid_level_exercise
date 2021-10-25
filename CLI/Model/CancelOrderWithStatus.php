<?php

namespace Magenest\CLI\Model;

use Magenest\CLI\Api\CancelOrderWithStatusInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\OrderManagementInterface;

/**
 * Class CancelOrderWithStatus
 */
class CancelOrderWithStatus implements CancelOrderWithStatusInterface
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var OrderManagementInterface
     */
    private $orderManagement;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderRepositoryInterface $orderRepository,
        DateTime $dateTime
//        OrderManagementInterface $orderManagement = null
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderRepository = $orderRepository;
        $this->dateTime = $dateTime;
//        $this->orderManagement = $orderManagement ?: ObjectManager::getInstance()->get(OrderManagementInterface::class);
    }

    /**
     * @inheirtDoc
     */
    public function execute($statusOrder)
    {
        $this->orderManagement = ObjectManager::getInstance()->get(OrderManagementInterface::class);
        if ($statusOrder == 'pending' || $statusOrder == 'processing') {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter(OrderInterface::STATUS, $statusOrder)
                ->addFilter(OrderInterface::UPDATED_AT, $this->dateTime->gmtDate('Y-m-d\TH:i:s\Z', strtotime('-1 hour')), 'lteq')
                ->create();
            $orders = $this->orderRepository->getList($searchCriteria)->getItems();
            foreach ($orders as $order) {
                $this->orderManagement->cancel((int)$order->getEntityId());
            }
            return true;
        } else {
            return false;
        }
    }
}
