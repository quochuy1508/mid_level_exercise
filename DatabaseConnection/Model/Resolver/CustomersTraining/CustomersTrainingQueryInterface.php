<?php

namespace Magenest\DatabaseConnection\Model\Resolver\CustomersTraining;

use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;

/**
 * Search for customer training by criteria
 */
interface CustomersTrainingQueryInterface
{
    /**
     * Get product search result
     *
     * @param array $args
     * @param ResolveInfo $info
     * @param ContextInterface $context
     * @return array
     */
    public function getResult(array $args, ResolveInfo $info, ContextInterface $context);
}
