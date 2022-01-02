<?php

namespace Magenest\DatabaseConnection\Model\Resolver;

use Magenest\DatabaseConnection\Model\Resolver\CustomersTraining\CustomersTrainingQueryInterface;
use Magento\CatalogGraphQl\Model\Resolver\Products\Query\ProductQueryInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\CatalogGraphQl\DataProvider\Product\SearchCriteriaBuilder;

/**
 * Products field resolver, used for GraphQL request processing.
 */
class CustomersTraining implements ResolverInterface
{
    /**
     * @var CustomersTrainingQueryInterface
     */
    private $searchQuery;

    /**
     * @param CustomersTrainingQueryInterface $searchQuery
     */
    public function __construct(
        CustomersTrainingQueryInterface $searchQuery
    ) {
        $this->searchQuery = $searchQuery;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $this->validateInput($args);

        $searchResult = $this->searchQuery->getResult($args, $info, $context);

        if ($searchResult['currentPage'] > $searchResult['totalPages'] && $searchResult['totalPages'] > 0) {
            throw new GraphQlInputException(
                __(
                    'currentPage value %1 specified is greater than the %2 page(s) available.',
                    [$searchResult['currentPage'], $searchResult['totalPages']]
                )
            );
        }

        return [
            'total_count' => $searchResult['totalCount'],
            'items' => $searchResult['searchResult'],
            'page_info' => [
                'page_size' => $searchResult['pageSize'],
                'current_page' => $searchResult['currentPage'],
                'total_pages' => $searchResult['totalPages']
            ]
        ];
    }

    /**
     * Validate input arguments
     *
     * @param array $args
     * @throws GraphQlInputException
     */
    private function validateInput(array $args)
    {
        if ($args['currentPage'] < 1) {
            throw new GraphQlInputException(__('currentPage value must be greater than 0.'));
        }
        if ($args['pageSize'] < 1) {
            throw new GraphQlInputException(__('pageSize value must be greater than 0.'));
        }
        if (!isset($args['filter'])) {
            throw new GraphQlInputException(
                __("'filter' input argument is required.")
            );
        }
    }
}
