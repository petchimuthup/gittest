<?php


namespace Tychons\Storeorder\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();

        $scheduler = $setup->getConnection()->newTable($setup->getTable('storeorder_scheduler'));

        $scheduler->addColumn(
            'scheduler_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        )
        ->addColumn(
            'userstore_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'store Id'
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
        )
        ->addColumn(
            'days_week',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'days_week'
        )
        ->addColumn(
            'time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'time'
        )->setComment("Scheduler Tables");;

        $setup->getConnection()->createTable($scheduler);

        //store order details

        $storeorder = $setup->getConnection()->newTable($setup->getTable('storeorder_details'));

        $storeorder->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
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

        $setup->getConnection()->createTable($storeorder);

        //create order attributes

        $orderTableItem = 'sales_order_item';
        $orderTable = 'sales_order';

        //Order table
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTableItem),
                'po_number',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'comment' =>'Purchase Order Number'
                ]
            );

        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTable),
                'order_store',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'comment' =>'order store type'
                ]
            );

        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTable),
                'company_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 11,
                    'comment' =>'store order id'
                ]
            );

        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTable),
                'customer_number',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'comment' =>'Store order customer type'
                ]
            );


       //create Customer password attributes


        $customerTable = 'customer_entity';

        //password table
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($customerTable),
                'reset_password',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 11,
                    'default' => '0',
                    'comment' =>'Password Reset'
                ]
            );


        $setup->endSetup();
    }
}
