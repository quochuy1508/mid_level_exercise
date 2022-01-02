<?php

namespace Magenest\DatabaseConnection\Model\Resolver\CustomersTraining\FilterArgument;

use Magento\Framework\GraphQl\Config\Element\Type;
use Magento\Framework\GraphQl\ConfigInterface;
use Magento\Framework\GraphQl\Query\Resolver\Argument\FieldEntityAttributesInterface;

/**
 * @inheritdoc
 */
class CustomerTrainingEntityAttributesForAst implements FieldEntityAttributesInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var array
     */
    private $additionalAttributes = [];

    /**
     * @param ConfigInterface $config
     * @param array $additionalAttributes
     */
    public function __construct(
        ConfigInterface $config,
        array $additionalAttributes = []
    ) {
        $this->config = $config;
        $this->additionalAttributes = array_merge($this->additionalAttributes, $additionalAttributes);
    }

    /**
     * @inheritdoc
     */
    public function getEntityAttributes(): array
    {
        $customerTrainingTypeSchema = $this->config->getConfigElement('CustomerTrainingOutput');
        if (!$customerTrainingTypeSchema instanceof Type) {
            throw new \LogicException(__("PickupLocation type not defined in schema."));
        }

        $fields = [];
        foreach ($customerTrainingTypeSchema->getFields() as $field) {
            $fields[$field->getName()] = [
                'type' => 'String',
                'fieldName' => $field->getName(),
            ];
        }

        foreach ($this->additionalAttributes as $attribute) {
            $fields[$attribute] = [
                'type' => 'String',
                'fieldName' => $attribute,
            ];
        }

        return $fields;
    }
}
