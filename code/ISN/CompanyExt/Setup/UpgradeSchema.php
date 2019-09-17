<?php

namespace ISN\CompanyExt\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface {
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context){
       /* $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            $installer->getConnection()->addColumn(
                $installer->getTable('company_isn'),
               'company_isn_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'size' => null,
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true,
                    'comment' => 'Company ISN Entity Id'
                ]
            );

            $installer->getConnection()->addColumn(
                $installer->getTable('company_isn'),
                'parent_company_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'size' => null,
                    'nullable' => false,
                    'comment' => 'Parent Company Entity Id'
                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable('company_isn'),
                'creation_time',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    'size' => null,
                    'comment' => 'Creation Time'
                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable('company_isn'),
                'update_time',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    'size' => null,
                    'comment' => 'Modification Time'
                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable('company_isn'),
                'is_active',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'size' => null,
                    'nullable' => false,
                    'default' => '1',
                    'comment' => 'Is Active'
                ]
            );
        }
        $installer->endSetup(); */
    }
}