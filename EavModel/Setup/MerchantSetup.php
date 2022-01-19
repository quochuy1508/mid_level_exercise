<?php

namespace Magenest\EavModel\Setup;


use Magento\Eav\Model\Entity\Attribute\Backend\Datetime;
use Magento\Eav\Setup\EavSetup;

class MerchantSetup extends EavSetup
{
    public function getDefaultEntities() {
        $bannerEntity = \Magenest\EavModel\Model\Merchant::ENTITY;
        $entities = [
            $bannerEntity => [
                'entity_model' => \Magenest\EavModel\Model\ResourceModel\Merchant::class,
                'table' => $bannerEntity . '_entity',
                'attribute_model' => \Magenest\EavModel\Model\Attribute::class,
                'increment_model' => null,
                'additional_attribute_table' => 'merchant_eav_attribute',
                'entity_attribute_collection' => \Magenest\EavModel\Model\ResourceModel\Attribute\Collection::class,
                'attributes' => [
                    'active_date' => [
                        'type' => 'datetime',
                        'label' => 'Active Date',
                        'input' => 'date',
                        'frontend' => \Magento\Eav\Model\Entity\Attribute\Frontend\Datetime::class,
                        'backend' => Datetime::class,
                        'required' => true,
                        'sort_order' => 10,
                        'visible' => true,
                        'input_filter' => 'date',
                        'validate_rules' => '{"input_validation":"date"}',
                    ],
                    'latest_updated_date' => [
                        'type' => 'datetime',
                        'label' => 'Latest Updated Date',
                        'input' => 'date',
                        'frontend' => \Magento\Eav\Model\Entity\Attribute\Frontend\Datetime::class,
                        'backend' => Datetime::class,
                        'required' => true,
                        'sort_order' => 20,
                        'visible' => true,
                        'validate_rules' => '{"input_validation":"date"}',
                    ],
                ]
            ],
        ];
        return $entities;
    }
}
