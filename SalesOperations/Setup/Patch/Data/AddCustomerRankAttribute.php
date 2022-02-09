<?php

namespace Magenest\SalesOperations\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class AddCustomerRankAttribute implements DataPatchInterface
{
    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;
    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * PatchInitial constructor.
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }


    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        /*customersetupfactory instead of eavsetupfactory */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);


        // for remove attribute
        //$customerSetup->removeAttribute(\Magento\Customer\Model\Customer::ENTITY,'address_book');

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();
        /** @var $attributeSet AttributeSet */
        $attributeSet = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        /* create customer Address book attribute */

        $customerSetup->addAttribute(
            Customer::ENTITY,'customer_rank',
            [
                'type' => 'varchar', // attribute with varchar type
                'label' => 'Customer Rank',
                'input' => 'select',
                'required' => false, // field is not required
                'visible' => true,
                'user_defined' => true,
                'system' => 0,
                'is_used_in_grid' => 1, //setting grid options
                'is_visible_in_grid' => 1,
                'is_filterable_in_grid' => 1,
                'is_searchable_in_grid' => 1,
                'source' => \Magenest\SalesOperations\Model\Customer\Attribute\Source\CustomerRank::class,
                'sort_order' => 10,
                'position' => 10,
                'adminhtml_only' => 1,
            ]
        );
        $sampleAttribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'customer_rank')
            ->addData(
                [
                    'attribute_set_id' => $attributeSetId,
                    'attribute_group_id' => $attributeGroupId,
                    'used_in_forms' => ['adminhtml_customer'],
                ]
            );
        $sampleAttribute->save();

    }
}
