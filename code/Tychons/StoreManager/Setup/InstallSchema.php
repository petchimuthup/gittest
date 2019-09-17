<?php


namespace Tychons\StoreManager\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) 
    {
            $installer = $setup;

            $installer->startSetup();

            if(!$installer->tableExists('user_store_select')) {

            $company = $installer->getConnection()->newTable(
                $installer->getTable('user_store_select')
            )

            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'Entity ID'
            )

            ->addColumn(
                'userstore_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'store id'
            )

            ->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'customer id'
            )

            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'store id'
            )
            ->addColumn(
                'website_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                [],
                'website_id'
            )->addForeignKey(
		$installer->getFkName('user_store_select', 'customer_id', 'customer_entity', 'entity_id'),
	        'customer_id',
		$installer->getTable('customer_entity'),
		'entity_id',
		\Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
		)

            ->setComment('User store select Table');

            $installer->getConnection()->createTable($company);

        }

        $installer->endSetup();

    }
}
