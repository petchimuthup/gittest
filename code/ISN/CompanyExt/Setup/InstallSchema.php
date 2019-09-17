<?php


namespace ISN\CompanyExt\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $installer = $setup;
        $installer->startSetup();

        $table = $installer->getConnection()->newTable(
            $installer->getTable('company_isn')
        )->addColumn(
            'company_isn_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
            'Company ISN Id'
        )->addColumn(
            'parent_company_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
             null,
            ['nullable' => true],
            'Parent Company Entity Id'
        )->addColumn(
            'creation_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
             null,
            [],
            //['nullable' => true, 'default' => \Magento\Framework\Db\Ddl\Table::TIMESTAMP_INIT],
            'Creation Time'
        )->addColumn(
            'update_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
             null,
            [],
            //['nullable' => true, 'default' => \Magento\Framework\Db\Ddl\Table::TIMESTAMP_INIT],
             'Modification Time'
        )->addColumn(
            'is_active',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            1,
            ['nullable' => true, 'default' => '1'],
            'Is Active'
        )->addColumn(
            'company_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            [ 'unsigned' => true, 'nullable' => false ],
            'Company FK'
        )->addColumn(
            'customer_number',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            10,
            ['nullable' => true],
            'Customer Number'
        )->addColumn(
            'parent_customer_number',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            10,
            ['nullable' => true],
            'Parent Customer Number'
        )->addColumn(
            'po_number_required',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            1,
            ['nullable' => true, 'default' => '1'],
            'PO Number Required'
        )->addColumn(
            'customer_group',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            10,
            ['nullable' => true],
            'Customer Group'
        )->addColumn(
            'credit_allow',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            1,
            ['nullable' => true, 'default' => '1'],
            'Credit Allow'
        )->addColumn(
            'account_allow',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            null,
            ['nullable' => true],
            'Account Allow'
        )->addColumn(
            'customer_price_group',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            10,
            ['nullable' => true],
            'Customer Price Group'
        )->addColumn(
            'customer_group',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            10,
            ['nullable' => true],
            'Customer Group'
        )->addColumn(
            'ship_to_address_attention',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            100,
            ['nullable' => true],
            'Ship Address ATTN'
        )->addColumn(
            'ship_to_address_phone',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            20,
            ['nullable' => true],
            'Ship Address Phone'
        )->addColumn(
            'ship_to_address_country',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Ship Address Country'
        )->addColumn(
            'ship_to_address_state',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Ship Address State'
        )->addColumn(
            'ship_to_address_zip',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            20,
            ['nullable' => true],
            'Ship Address Zip Code'
        )->addColumn(
            'ship_to_address_city',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Ship Address City'
        )->addColumn(
            'ship_to_address_1',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Ship Address 1'
        )->addColumn(
            'ship_to_address_2',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Ship Address 2'
        )->addColumn(
            'ship_to_address_email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Ship Address Email'
        )->addColumn(
           'website_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            [ 'unsigned' => true, 'nullable' => false ],
            'website id'
        )->addForeignKey(
            $installer->getFkName('company_isn', 'company_id', 'company', 'entity_id'),
            'company_id',
            $installer->getTable('company'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );

        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }
}
