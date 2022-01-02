<?php
/**
 * Copyright © 2019 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\DatabaseConnection\Setup\Patch\Schema;


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Table CreateTable
 */
class CreateTable implements SchemaPatchInterface
{
    const CUSTOM_CONNECTION = 'custom';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheirtDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheirtDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheirtDoc
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $table = $this->moduleDataSetup->getConnection(self::CUSTOM_CONNECTION)->newTable(
            $this->moduleDataSetup->getTable('customer_training')
        )
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                ],
                'Entity ID'
            )
            ->addColumn(
                'first_name',
                Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'First Name'
            )
            ->addColumn(
                'last_name',
                Table::TYPE_TEXT,
                '255',
                ['nullable => false'],
                'Last name'
            )
            ->addColumn(
                'address',
                Table::TYPE_TEXT,
                '1000',
                ['nullable => false'],
                'Address'
            )
            ->addColumn(
                'city',
                Table::TYPE_TEXT,
                '255',
                ['nullable => false'],
                'City'
            )
            ->addColumn(
                'age',
                Table::TYPE_INTEGER,
                1,
                [],
                'Age'
            )
            ->setComment('Customer Training Table');

        $this->moduleDataSetup->getConnection(self::CUSTOM_CONNECTION)->createTable($table);

        $this->moduleDataSetup->endSetup();
    }
}

