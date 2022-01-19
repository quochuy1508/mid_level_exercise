<?php

namespace Magenest\EavModel\Setup\Patch\Data;

use Magenest\EavModel\Model\Merchant;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Category as CategoryFormHelper;
use Magento\Catalog\Model\Product\Attribute\Backend\Category as CategoryBackendAttribute;
use Magento\Catalog\Model\Product\Attribute\Source\Status;

/**
 * Class AddAttributeForMerchant
 *
 */
class AddAttributeForMerchant implements DataPatchInterface, PatchRevertableInterface
{
    const ATTRIBUTE = [
        'category_ids' => [
            'type' => 'varchar',
            'label' => 'Categories',
            'input' => 'select',
            'required' => true,
            'sort_order' => 0,
            'visible' => true,
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
    ];

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        foreach (self::ATTRIBUTE as $key => $value) {
            $eavSetup->addAttribute(Merchant::ENTITY, $key, $value);
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'color');

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [InstallDefaultMerchant::class];
    }
}
