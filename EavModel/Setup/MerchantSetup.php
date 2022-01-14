<?php

namespace Magenest\EavModel\Setup;

use Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Category as CategoryFormHelper;
use Magento\Catalog\Model\Product\Attribute\Backend\Category as CategoryBackendAttribute;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Eav\Model\Entity\Attribute\Backend\Datetime;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;

class MerchantSetup extends  EavSetup
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
//                    'category_ids' => [
//                        'type' => 'static',
//                        'label' => 'Categories',
//                        'input' => 'select',
//                        'backend' => CategoryBackendAttribute::class,
//                        'input_renderer' => CategoryFormHelper::class,
//                        'required' => true,
//                        'sort_order' => 0,
//                        'visible' => true,
//                    ],
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
//                    'merchant_status' => [
//                        'type' => 'static',
//                        'label' => 'Merchant Status',
//                        'input' => 'select',
//                        'source' => Status::class,
//                        'sort_order' => 30,
//                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
//                        'group' => 'General',
//                    ],
//                    'kyc_level' => [
//                        'type' => 'static',
//                        'label' => 'KYC Level',
//                        'input' => 'text',
//                        'sort_order' => 40,
//                        'global' => ScopedAttributeInterface::SCOPE_STORE,
//                        'group' => 'General',
//                    ],
//                    'merchant_type' => [
//                        'type' => 'static',
//                        'label' => 'Merchant Type',
//                        'input' => 'text',
//                        'sort_order' => 50,
//                        'global' => ScopedAttributeInterface::SCOPE_STORE,
//                        'group' => 'General',
//                    ],
//                    'partner' => [
//                        'type' => 'static',
//                        'label' => 'Partner',
//                        'input' => 'text',
//                        'sort_order' => 60,
//                        'global' => ScopedAttributeInterface::SCOPE_STORE,
//                        'group' => 'General',
//                    ],
//                    'mc_phone_no' => [
//                        'type' => 'static',
//                        'label' => 'MC\'s Phone No',
//                        'input' => 'text',
//                        'sort_order' => 70,
//                        'global' => ScopedAttributeInterface::SCOPE_STORE,
//                        'group' => 'General',
//                    ],
//                    'dsa_phone_no' => [
//                        'type' => 'static',
//                        'label' => 'DSA\'s Phone No',
//                        'input' => 'text',
//                        'sort_order' => 80,
//                        'global' => ScopedAttributeInterface::SCOPE_STORE,
//                        'group' => 'General',
//                    ],
                ]
            ],
        ];
        return $entities;
    }
}
