<?php

namespace Magenest\EavModel\Setup\Patch\Data;

use Magento\Catalog\Setup\CategorySetup;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magenest\EavModel\Setup\MerchantSetupFactory;

class InstallDefaultMerchant implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var MerchantSetupFactory
     */
    private $merchantSetupFactory;

    /**
     * PatchInitial constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param MerchantSetupFactory $merchantSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        MerchantSetupFactory     $merchantSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->merchantSetupFactory = $merchantSetupFactory;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        /** @var CategorySetup $merchantSetup */
        $merchantSetup = $this->merchantSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $merchantSetup->installEntities();
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
