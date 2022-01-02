<?php

namespace Magenest\DatabaseConnection\Model\Resolver;

use Magenest\DatabaseConnection\Api\CustomerTrainingRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class  CustomerTraining implements ResolverInterface
{
    /**
     * @var CustomerTrainingRepositoryInterface
     */
    private $customerTrainingRepository;

    /**
     *
     * @param CustomerTrainingRepositoryInterface $customerTrainingRepository
     */
    public function __construct(
        CustomerTrainingRepositoryInterface $customerTrainingRepository
    ) {
        $this->customerTrainingRepository = $customerTrainingRepository;
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
        if (!isset($args['id'])) {
            throw new GraphQlInputException(__('"Customer id should be specified'));
        }

        $customerTrainingData = [];

        try {
            if (isset($args['id'])) {
                $customerTrainingData = $this->customerTrainingRepository->getById((int)$args['id']);
            }
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $customerTrainingData;
    }
}
