<?php

namespace Magenest\CustomAdmin\Model\Queue;

use Magenest\CustomAdmin\Api\Data\OperationInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Consumer
 */
class Consumer
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param array $datas
     */
    public function process(OperationInterface $operation)
    {
        try {
            $this->logger->info($operation->getCustomerIds());
        } catch (\Exception $e) {
        	$this->logger->critical($e->getMessage());
    	}
	}
}
