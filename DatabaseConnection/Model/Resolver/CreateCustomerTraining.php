<?php

namespace Magenest\DatabaseConnection\Model\Resolver;

use Magenest\DatabaseConnection\Api\CustomerTrainingRepositoryInterface;
use Magenest\DatabaseConnection\Model\CustomerTraining;
use Magenest\DatabaseConnection\Model\CustomerTrainingFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class CreateCustomerTraining implements ResolverInterface
{
    /**
     * @var CustomerTrainingRepositoryInterface
     */
    private $customerTrainingRepository;

    /**
     * @var CustomerTrainingFactory
     */
    private $customerTrainingFactory;

    /**
     *
     * @param CustomerTrainingRepositoryInterface $customerTrainingRepository
     * @param CustomerTrainingFactory $customerTrainingFactory
     */
    public function __construct(
        CustomerTrainingRepositoryInterface $customerTrainingRepository,
        CustomerTrainingFactory $customerTrainingFactory
    ) {
        $this->customerTrainingRepository = $customerTrainingRepository;
        $this->customerTrainingFactory = $customerTrainingFactory;
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
        try {
            /** @var CustomerTraining $model */
            $model = $this->customerTrainingFactory->create();
            $model->setData($args['input']);
            $customerTrainingData = $this->customerTrainingRepository->save($model);
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $customerTrainingData;
    }
}
