<?php

namespace Magenest\ElasticSearch\Plugin;

use Magento\Framework\Search\Request\QueryInterface as RequestQueryInterface;

class AddWildcardQuery
{
    /**
     * @param \Magento\Elasticsearch\SearchAdapter\Query\Builder\Match $subject
     * @param $result
     * @param array $selectQuery
     * @param RequestQueryInterface $requestQuery
     * @param $conditionType
     * @return mixed
     */
    public function afterBuild(
        \Magento\Elasticsearch\SearchAdapter\Query\Builder\Match $subject,
        $result,
        array $selectQuery,
        RequestQueryInterface $requestQuery,
        $conditionType
    ) {
        if ($requestQuery->getName() === 'search') {
            foreach ($result as &$items) {
                foreach ($items['should'] as $index => &$item) {
                    $key = array_key_first($item['match']);
                    $items['should'][$index]['wildcard'] = [
                        $key => '*' . $item['match'][$key]['query'] . '*'
                    ];
                    unset($items['should'][$index]['match']);
                }
            }
        }
        return $result;
    }
}
