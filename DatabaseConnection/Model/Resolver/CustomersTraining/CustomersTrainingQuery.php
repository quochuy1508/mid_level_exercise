<?php

namespace Magenest\DatabaseConnection\Model\Resolver\CustomersTraining;

use Magenest\DatabaseConnection\Api\CustomerTrainingRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;
use Magento\Search\Model\Query;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\GraphQl\Query\Resolver\ArgumentsProcessorInterface;

/**
 * Retrieve filtered product data based off given search criteria in a format that GraphQL can interpret.
 */
class CustomersTrainingQuery implements CustomersTrainingQueryInterface
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ArgumentsProcessorInterface
     */
    private $argsSelection;

    /**
     * @var CustomerTrainingRepositoryInterface
     */
    private $customerTrainingRepository;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ScopeConfigInterface $scopeConfig
     * @param CustomerTrainingRepositoryInterface $customerTrainingRepository
     * @param ArgumentsProcessorInterface|null $argsSelection
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ScopeConfigInterface $scopeConfig,
        CustomerTrainingRepositoryInterface $customerTrainingRepository,
        ArgumentsProcessorInterface $argsSelection = null
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->customerTrainingRepository = $customerTrainingRepository;
        $this->argsSelection = $argsSelection ? : ObjectManager::getInstance()
            ->get(ArgumentsProcessorInterface::class);
    }

    /**
     * Filter catalog product data based off given search criteria
     *
     * @param array $args
     * @param ResolveInfo $info
     * @param ContextInterface $context
     * @return array
     * @throws GraphQlInputException
     */
    public function getResult(
        array $args,
        ResolveInfo $info,
        ContextInterface $context
    ) {
        try {
            $searchCriteria = $this->buildSearchCriteria($info->fieldName, $args);
            $searchResults = $this->customerTrainingRepository->getList($searchCriteria);
        } catch (InputException $e) {
            return $this->createEmptyResult((int)$args['pageSize'], (int)$args['currentPage']);
        }

        $customerTrainingArray = [];
        foreach ($searchResults->getItems() as $item) {
            $customerTrainingArray[$item->getId()] = $item->getData();
            $customerTrainingArray[$item->getId()]['model'] = $item;
        }

        //possible division by 0
        if ($searchCriteria->getPageSize()) {
            $maxPages = (int)ceil($searchResults->getTotalCount() / $searchCriteria->getPageSize());
        } else {
            $maxPages = 0;
        }

        return [
            'totalCount' => $searchResults->getTotalCount(),
            'searchResult' => $customerTrainingArray,
            'pageSize' => $searchCriteria->getPageSize(),
            'currentPage' => $searchCriteria->getCurrentPage(),
            'totalPages' => $maxPages,
        ];
    }

    /**
     * Build search criteria from query input args
     *
     * @param string $fieldName
     * @param array $args
     * @return SearchCriteriaInterface
     * @throws GraphQlInputException
     * @throws InputException
     */
    private function buildSearchCriteria(string $fieldName, array $args): SearchCriteriaInterface
    {
        $processedArgs = $this->argsSelection->process($fieldName, $args);
        if (!empty($processedArgs['filter'])) {
            $processedArgs['filter'] = $this->formatFilters($processedArgs['filter']);
        }

        $criteria = $this->searchCriteriaBuilder->build($fieldName, $processedArgs);
        $criteria->setCurrentPage($processedArgs['currentPage']);
        $criteria->setPageSize($processedArgs['pageSize']);

        return $criteria;
    }

    /**
     * Reformat filters
     *
     * @param array $filters
     * @return array
     * @throws InputException
     */
    private function formatFilters(array $filters): array
    {
        $formattedFilters = [];

        foreach ($filters as $field => $filter) {
            foreach ($filter as $condition => $value) {
                if ($condition === 'match') {
                    // reformat 'match' filter so MySQL filtering behaves like SearchAPI filtering
                    $condition = 'like';
                    $value = str_replace('%', '', trim($value));
                    $value = '%' . preg_replace('/ +/', '%', $value) . '%';
                }
                $formattedFilters[$field] = [$condition => $value];
            }
        }

        return $formattedFilters;
    }

    /**
     * Return and empty SearchResult object
     *
     * Used for handling exceptions gracefully
     *
     * @param int $pageSize
     * @param int $currentPage
     * @return array
     */
    private function createEmptyResult(int $pageSize, int $currentPage)
    {
        return [
            'totalCount' => 0,
            'productsSearchResult' => [],
            'pageSize' => $pageSize,
            'currentPage' => $currentPage,
            'totalPages' => 0,
        ];
    }
}
