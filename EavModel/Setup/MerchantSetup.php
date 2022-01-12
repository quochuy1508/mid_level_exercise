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
                'attributes' => [
                    'category_ids' => [
                        'type' => 'static',
                        'label' => 'Categories',
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'backend' => CategoryBackendAttribute::class,
                        'input_renderer' => CategoryFormHelper::class,
                        'required' => true,
                        'sort_order' => 0,
                        'visible' => true,
                        'group' => 'General',
                    ],
                    'active_date' => [
                        'type' => 'datetime',
                        'label' => 'Active Date',
                        'input' => 'date',
                        'backend' => Datetime::class,
                        'required' => true,
                        'sort_order' => 10,
                        'visible' => true,
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'group' => 'General',
                    ],
                    'latest_updated_date' => [
                        'type' => 'datetime',
                        'label' => 'Latest Updated Date',
                        'input' => 'date',
                        'backend' => Datetime::class,
                        'required' => true,
                        'sort_order' => 20,
                        'visible' => true,
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'group' => 'General',
                    ],
                    'merchant_status' => [
                        'type' => 'int',
                        'label' => 'Merchant Status',
                        'input' => 'select',
                        'source' => Status::class,
                        'sort_order' => 30,
                        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                        'group' => 'General',
                    ],
                    'kyc_level' => [
                        'type' => 'varchar',
                        'label' => 'KYC Level',
                        'input' => 'text',
                        'sort_order' => 40,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General',
                    ],
                    'merchant_type' => [
                        'type' => 'varchar',
                        'label' => 'Merchant Type',
                        'input' => 'text',
                        'sort_order' => 50,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General',
                    ],
                    'partner' => [
                        'type' => 'varchar',
                        'label' => 'Partner',
                        'input' => 'text',
                        'sort_order' => 60,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General',
                    ],
                    'mc_phone_no' => [
                        'type' => 'varchar',
                        'label' => 'MC\'s Phone No',
                        'input' => 'text',
                        'sort_order' => 70,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General',
                    ],
                    'dsa_phone_no' => [
                        'type' => 'varchar',
                        'label' => 'DSA\'s Phone No',
                        'input' => 'text',
                        'sort_order' => 80,
                        'global' => ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General',
                    ],
                ]
            ],
        ];
        return $entities;
    }
}
