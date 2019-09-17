<?php


namespace Tychons\Favoriteorder\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    )
    {


        $favoriteorder = $setup->getConnection()->newTable($setup->getTable('favoriteorder_details'));

        $favoriteorder->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true,],
            'Entity ID'
        )
            ->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'Customer Id'
            )
            ->addColumn(
                'role_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'Role Id'
            )
            ->addColumn(
                'userstore_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'store Id'
            )
            ->addColumn(
                'product_id_qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'Product Id Qty'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'magento store Id'
            )
            ->addColumn(
                'website_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'website Id'
            )->setComment("store order Tables");

        $setup->getConnection()->createTable($favoriteorder);


    }
}
