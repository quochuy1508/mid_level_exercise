<?php

namespace Magenest\EavModel\Setup\Patch\Data;

use Magenest\EavModel\Model\Merchant\Attribute\Source;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddAttributeMerchantForProduct implements DataPatchInterface
{
    const MERCHANT_ID = "merchant_id";

    /** @var ModuleDataSetupInterface */
    private $setup;

    /** @var EavSetupFactory */
    private $eavSetupFactory;

    /**
     * @param ModuleDataSetupInterface $setup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $setup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->setup = $setup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @return void
     * @throws LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->setup]);
        $this->addMerchantIdAttribute($eavSetup);
    }

    /**
     * @param EavSetup $eavSetup
     * @throws LocalizedException
     * @throws \Zend_Validate_Exception
     */
    private function addMerchantIdAttribute(EavSetup $eavSetup)
    {
        $config = [
            'type' => 'int',
            'label' => 'Merchant',
            'input' => 'select',
            'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
            'source' => Source::class,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'sort_order' => 250,
            'searchable' => true,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'visible_in_advanced_search' => true,
            'used_in_product_listing' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => true,
            'unique' => false
        ];
        if (!$eavSetup->getAttribute(Product::ENTITY, self::MERCHANT_ID)) {
            $eavSetup->addAttribute(Product::ENTITY, self::MERCHANT_ID, $config);
        } else {
            $eavSetup->updateAttribute(Product::ENTITY, self::MERCHANT_ID, $config);
        }
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
}
