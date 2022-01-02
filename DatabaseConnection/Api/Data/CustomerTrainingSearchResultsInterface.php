<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\DatabaseConnection\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for cms page search results.
 * @api
 * @since 100.0.2
 */
interface CustomerTrainingSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get pages list.
     *
     * @return \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface[]
     */
    public function getItems();

    /**
     * Set pages list.
     *
     * @param \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
