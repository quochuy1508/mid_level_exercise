<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\DatabaseConnection\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Customer Training CRUD interface.
 * @api
 * @since 100.0.2
 */
interface CustomerTrainingRepositoryInterface
{
    /**
     * Save customerTraining.
     *
     * @param \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface $customerTraining
     * @return \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface $customerTraining);

    /**
     * Retrieve customerTraining.
     *
     * @param int $id
     * @return \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($id);

    /**
     * Retrieve customerTrainings matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magenest\DatabaseConnection\Api\Data\CustomerTrainingSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete customerTraining.
     *
     * @param \Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface $customerTraining
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Magenest\DatabaseConnection\Api\Data\CustomerTrainingInterface $customerTraining);

    /**
     * Delete customerTraining by ID.
     *
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($id);
}
